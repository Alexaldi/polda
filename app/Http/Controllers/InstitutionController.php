<?php

namespace App\Http\Controllers;

use App\Services\InstitutionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InstitutionController extends Controller
{
    protected $service;
    protected $fiture_title;
    protected $fiture_name;
    protected $fiture_path;
    protected $user;

    public function __construct(InstitutionService $service)
    {
        $this->service = $service;
        $this->fiture_title = 'Institusi Management';
        $this->fiture_name = 'Institusi';
        $this->fiture_path = 'institution';

        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
    }

    public function index()
    {
        $types = $this->service->getTypes()->values();

        return view('pages.institutions.index', [
            'title' => $this->fiture_title,
            'name' => $this->fiture_name,
            'path' => $this->fiture_path,
            'user' => $this->user,
            'types' => $types,
        ]);
    }

    public function datatables(Request $request)
    {
        if ($request->ajax()) {
            $columns = [
                null,
                'institutions.name',
                'institutions.type',
                'institutions.created_at',
                null,
            ];

            $limit = (int) $request->input('length');
            $start = (int) $request->input('start');
            $orderColumnIndex = (int) $request->input('order.0.column');
            $order = $columns[$orderColumnIndex] ?? 'institutions.created_at';
            $dir = $request->input('order.0.dir', 'desc');

            $posts = $this->service->getAllForDatatable();

            $filterQ = $request->input('filter_q');
            if (!empty($filterQ)) {
                $posts = $posts->where(function ($q) use ($filterQ) {
                    $q->where('institutions.name', 'like', '%' . $filterQ . '%')
                        ->orWhere('institutions.type', 'like', '%' . $filterQ . '%');
                });
            }

            $filterType = $request->input('filter_type');
            if (!empty($filterType)) {
                $posts = $posts->where('institutions.type', $filterType);
            }

            $totalData = $posts->count();

            if ($order) {
                $posts = $posts->orderBy($order, $dir);
            }

            if ($limit > 0) {
                $posts = $posts->skip($start)->take($limit);
            }

            $records = $posts->get();

            $data = [];
            foreach ($records as $index => $institution) {
                $actionHtml = '<td class="text-nowrap">'
                    . '<a href="' . route('institutions.edit', $institution->id) . '"'
                    . ' class="btn btn-warning btn-sm content-icon btn-edit" data-id="' . $institution->id . '">'
                    . '    <i class="fa fa-edit"></i>'
                    . '</a>'
                    . ' <a href="javascript:void(0);"'
                    . ' class="btn btn-danger btn-sm content-icon btn-delete"'
                    . ' data-id="' . $institution->id . '"'
                    . ' data-name="' . htmlspecialchars($institution->name ?? '', ENT_QUOTES) . '"'
                    . ' data-url="' . route('institutions.destroy', $institution->id) . '"'
                    . ' data-title="Hapus Institusi?">'
                    . '    <i class="fa fa-times"></i>'
                    . '</a>'
                    . '</td>';

                $data[] = [
                    'DT_RowIndex' => $start + $index + 1,
                    'name' => $institution->name ?? '-',
                    'type' => $institution->type ?? '-',
                    'created_at' => backChangeFormatDate($institution->created_at) ?? '-',
                    'action' => $actionHtml,
                ];
            }

            return response()->json([
                'draw' => (int) $request->input('draw'),
                'recordsTotal' => (int) $totalData,
                'recordsFiltered' => (int) $totalData,
                'data' => $data,
            ]);
        }

        abort(404);
    }

    public function create()
    {
        $types = $this->service->getTypes()->values();

        return view('pages.institutions.create', [
            'title' => $this->fiture_title,
            'name' => $this->fiture_name,
            'path' => $this->fiture_path,
            'user' => $this->user,
            'types' => $types,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:institutions,name'],
            'type' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();

        try {
            $result = $this->service->store($validated);
            if (!$result['status']) {
                DB::rollBack();

                return redirect()->back()->withInput()->with('error', $result['message']);
            }

            DB::commit();

            return redirect()->route('institutions.index')->with('success', $result['message']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function show($id)
    {
        $institution = $this->service->getById($id);

        if (!$institution) {
            abort(404);
        }

        return redirect()->route('institutions.edit', $institution->id);
    }

    public function edit($id)
    {
        $institution = $this->service->getById($id);

        if (!$institution) {
            abort(404);
        }

        $types = $this->service->getTypes();
        if ($institution->type && !$types->contains($institution->type)) {
            $types->push($institution->type);
        }

        return view('pages.institutions.create', [
            'title' => $this->fiture_title,
            'name' => $this->fiture_name,
            'path' => $this->fiture_path,
            'user' => $this->user,
            'types' => $types->sort()->values(),
            'institution' => $institution,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('institutions', 'name')->ignore($id)],
            'type' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();

        try {
            $result = $this->service->update($id, $validated);
            if (!$result['status']) {
                DB::rollBack();

                return redirect()->back()->withInput()->with('error', $result['message']);
            }

            DB::commit();

            return redirect()->route('institutions.index')->with('success', $result['message']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->service->delete($id);

            $statusCode = $result['status'] ? 200 : 404;

            return response()->json([
                'status' => $result['status'],
                'message' => $result['message'],
            ], $statusCode);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
