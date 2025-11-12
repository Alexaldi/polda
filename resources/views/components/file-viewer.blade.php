<div class="modal fade" id="fileViewerModal" tabindex="-1" aria-labelledby="fileViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="fileViewerModalLabel">Pratinjau Bukti</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body position-relative">
                <div class="viewer-loading d-flex flex-column align-items-center justify-content-center py-5 d-none" data-viewer-loading>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>
                    <p class="mt-3 mb-0 text-muted small">Sedang menyiapkan pratinjau...</p>
                </div>

                <div class="viewer-pdf d-none" data-viewer-pdf>
                    <div class="pdf-toolbar d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3" data-pdf-toolbar>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Navigasi halaman PDF">
                            <button type="button" class="btn btn-outline-primary" data-pdf-prev>
                                <i class="fa fa-angle-left"></i>
                            </button>
                            <button type="button" class="btn btn-outline-primary" data-pdf-next>
                                <i class="fa fa-angle-right"></i>
                            </button>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge rounded-pill bg-primary" data-pdf-page-indicator>Halaman 1 / 1</span>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Kontrol zoom PDF">
                                <button type="button" class="btn btn-outline-primary" data-pdf-zoom-out>
                                    <i class="fa fa-search-minus"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-pdf-zoom-reset>
                                    <i class="fa fa-compress"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-pdf-zoom-in>
                                    <i class="fa fa-search-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="pdf-canvas-wrapper">
                        <canvas data-pdf-canvas></canvas>
                    </div>
                </div>

                <div class="viewer-image text-center d-none" data-viewer-image>
                    <img src="" alt="Pratinjau file" class="img-fluid rounded shadow" data-viewer-image-el referrerpolicy="no-referrer" />
                </div>

                <div class="viewer-docx d-none" data-viewer-docx></div>

                <div class="viewer-message d-none" data-viewer-message></div>
            </div>
            <div class="modal-footer d-flex justify-content-between flex-wrap gap-2">
                <a
                    href="#"
                    class="btn btn-primary"
                    target="_blank"
                    rel="noopener"
                    download
                    data-download-button
                >
                    <i class="fa fa-download me-1"></i> Unduh File
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@once
    <style>
        #fileViewerModal .modal-content {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        body.dark-version #fileViewerModal .modal-content,
        body[data-bs-theme="dark"] #fileViewerModal .modal-content {
            background-color: #0d1117;
            color: #f1f5f9;
        }

        #fileViewerModal .modal-body {
            min-height: 40vh;
        }

        #fileViewerModal .pdf-canvas-wrapper {
            background-color: rgba(15, 23, 42, 0.03);
            border-radius: 0.75rem;
            padding: 1rem;
            max-height: 70vh;
            overflow: auto;
            display: flex;
            justify-content: center;
        }

        body.dark-version #fileViewerModal .pdf-canvas-wrapper,
        body[data-bs-theme="dark"] #fileViewerModal .pdf-canvas-wrapper {
            background-color: rgba(15, 23, 42, 0.45);
        }

        #fileViewerModal canvas[data-pdf-canvas] {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.1);
            width: 100%;
            height: auto !important;
        }

        body.dark-version #fileViewerModal canvas[data-pdf-canvas],
        body[data-bs-theme="dark"] #fileViewerModal canvas[data-pdf-canvas] {
            background-color: #1f2937;
        }

        #fileViewerModal .viewer-docx {
            max-height: 70vh;
            overflow-y: auto;
            padding: 1rem;
            background-color: rgba(15, 23, 42, 0.03);
            border-radius: 0.75rem;
        }

        body.dark-version #fileViewerModal .viewer-docx,
        body[data-bs-theme="dark"] #fileViewerModal .viewer-docx {
            background-color: rgba(15, 23, 42, 0.45);
            color: #f1f5f9;
        }

        #fileViewerModal .viewer-docx h1,
        #fileViewerModal .viewer-docx h2,
        #fileViewerModal .viewer-docx h3,
        #fileViewerModal .viewer-docx h4,
        #fileViewerModal .viewer-docx h5,
        #fileViewerModal .viewer-docx h6 {
            margin-top: 1.25rem;
            margin-bottom: 0.75rem;
        }

        #fileViewerModal .viewer-message {
            max-height: 70vh;
        }

        #fileViewerModal .pdf-toolbar .btn,
        #fileViewerModal .pdf-toolbar .badge {
            font-weight: 600;
        }

        #fileViewerModal .viewer-image img {
            max-height: 70vh;
            object-fit: contain;
        }
    </style>
@endonce

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('fileViewerModal');
            if (!modalEl) {
                return;
            }

            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            const loadingWrapper = modalEl.querySelector('[data-viewer-loading]');
            const pdfWrapper = modalEl.querySelector('[data-viewer-pdf]');
            const pdfCanvas = modalEl.querySelector('[data-pdf-canvas]');
            const pdfContext = pdfCanvas.getContext('2d');
            const pdfPrevBtn = modalEl.querySelector('[data-pdf-prev]');
            const pdfNextBtn = modalEl.querySelector('[data-pdf-next]');
            const pdfZoomOutBtn = modalEl.querySelector('[data-pdf-zoom-out]');
            const pdfZoomResetBtn = modalEl.querySelector('[data-pdf-zoom-reset]');
            const pdfZoomInBtn = modalEl.querySelector('[data-pdf-zoom-in]');
            const pdfPageIndicator = modalEl.querySelector('[data-pdf-page-indicator]');
            const imageWrapper = modalEl.querySelector('[data-viewer-image]');
            const imageEl = modalEl.querySelector('[data-viewer-image-el]');
            const docxWrapper = modalEl.querySelector('[data-viewer-docx]');
            const messageWrapper = modalEl.querySelector('[data-viewer-message]');
            const downloadButton = modalEl.querySelector('[data-download-button]');

            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            const officeExtensions = ['doc', 'docx'];
            const pdfExtension = 'pdf';

            const PDFJS_SRC = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.2.67/pdf.min.js';
            const PDFJS_WORKER_SRC = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.2.67/pdf.worker.min.js';
            const MAMMOTH_SRC = 'https://cdn.jsdelivr.net/npm/mammoth@1.6.0/mammoth.browser.min.js';

            let currentUrl = null;
            let currentFileName = null;
            let pdfState = {
                doc: null,
                page: 1,
                total: 0,
                zoom: 1,
                renderTask: null,
            };

            function loadScriptOnce(src) {
                return new Promise(function (resolve, reject) {
                    let existing = document.querySelector('script[data-dynamic-src="' + src + '"]');

                    if (existing) {
                        if (existing.getAttribute('data-loaded') === 'true') {
                            resolve();
                            return;
                        }

                        existing.addEventListener('load', function () { resolve(); }, { once: true });
                        existing.addEventListener('error', function () { reject(new Error('Gagal memuat skrip: ' + src)); }, { once: true });
                        return;
                    }

                    const script = document.createElement('script');
                    script.src = src;
                    script.async = true;
                    script.setAttribute('data-dynamic-src', src);
                    script.addEventListener('load', function () {
                        script.setAttribute('data-loaded', 'true');
                        resolve();
                    }, { once: true });
                    script.addEventListener('error', function () {
                        script.remove();
                        reject(new Error('Gagal memuat skrip: ' + src));
                    }, { once: true });

                    document.head.appendChild(script);
                });
            }

            function ensurePdfJs() {
                if (window.pdfjsLib) {
                    window.pdfjsLib.GlobalWorkerOptions.workerSrc = PDFJS_WORKER_SRC;
                    return Promise.resolve();
                }

                return loadScriptOnce(PDFJS_SRC).then(function () {
                    if (!window.pdfjsLib) {
                        throw new Error('pdf.js tidak tersedia');
                    }
                    window.pdfjsLib.GlobalWorkerOptions.workerSrc = PDFJS_WORKER_SRC;
                });
            }

            function ensureMammoth() {
                if (window.mammoth) {
                    return Promise.resolve();
                }

                return loadScriptOnce(MAMMOTH_SRC).then(function () {
                    if (!window.mammoth) {
                        throw new Error('mammoth.js tidak tersedia');
                    }
                });
            }

            function resetPdfState() {
                if (pdfState.renderTask && typeof pdfState.renderTask.cancel === 'function') {
                    pdfState.renderTask.cancel();
                }
                pdfState = {
                    doc: null,
                    page: 1,
                    total: 0,
                    zoom: 1,
                    renderTask: null,
                };
                pdfCanvas.width = 0;
                pdfCanvas.height = 0;
                pdfCanvas.style.width = '';
                pdfCanvas.style.height = '';
            }

            function hideAll() {
                [loadingWrapper, pdfWrapper, imageWrapper, docxWrapper, messageWrapper].forEach(function (element) {
                    if (!element) {
                        return;
                    }
                    element.classList.add('d-none');
                });
            }

            function showLoading() {
                hideAll();
                if (loadingWrapper) {
                    loadingWrapper.classList.remove('d-none');
                }
            }

            function showMessage(message, type = 'info') {
                hideAll();
                if (!messageWrapper) {
                    return;
                }

                const alertClass = type === 'danger' ? 'alert-danger' : (type === 'warning' ? 'alert-warning' : 'alert-info');
                messageWrapper.innerHTML = '<div class="alert ' + alertClass + ' text-center mb-0">' + message + '</div>';
                messageWrapper.classList.remove('d-none');
            }

            function stopLoading() {
                if (loadingWrapper) {
                    loadingWrapper.classList.add('d-none');
                }
            }

            function updatePdfControls() {
                if (!pdfState.doc) {
                    return;
                }
                if (pdfPrevBtn) {
                    pdfPrevBtn.disabled = pdfState.page <= 1;
                }
                if (pdfNextBtn) {
                    pdfNextBtn.disabled = pdfState.page >= pdfState.total;
                }
                if (pdfZoomOutBtn) {
                    pdfZoomOutBtn.disabled = pdfState.zoom <= 0.6;
                }
                if (pdfZoomInBtn) {
                    pdfZoomInBtn.disabled = pdfState.zoom >= 2.4;
                }
                if (pdfPageIndicator) {
                    pdfPageIndicator.textContent = 'Halaman ' + pdfState.page + ' / ' + pdfState.total;
                }
            }

            function renderPdfPage() {
                if (!pdfState.doc) {
                    return;
                }

                stopLoading();
                pdfWrapper.classList.remove('d-none');

                pdfState.doc.getPage(pdfState.page).then(function (page) {
                    const viewport = page.getViewport({ scale: pdfState.zoom });
                    const outputScale = window.devicePixelRatio || 1;
                    const canvas = pdfCanvas;
                    const context = pdfContext;

                    canvas.width = viewport.width * outputScale;
                    canvas.height = viewport.height * outputScale;
                    canvas.style.width = viewport.width + 'px';
                    canvas.style.height = viewport.height + 'px';

                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport,
                    };

                    if (outputScale !== 1) {
                        renderContext.transform = [outputScale, 0, 0, outputScale, 0, 0];
                    }

                    if (pdfState.renderTask && typeof pdfState.renderTask.cancel === 'function') {
                        pdfState.renderTask.cancel();
                    }

                    pdfState.renderTask = page.render(renderContext);
                    return pdfState.renderTask.promise;
                }).then(function () {
                    pdfState.renderTask = null;
                    updatePdfControls();
                }).catch(function (error) {
                    if (error && error.name === 'RenderingCancelledException') {
                        return;
                    }
                    console.error('Galat saat merender PDF:', error);
                    showMessage('Pratinjau PDF tidak dapat dimuat. Gunakan tombol unduh di bawah.', 'warning');
                });
            }

            function openPdf(url) {
                showLoading();
                resetPdfState();

                ensurePdfJs()
                    .then(function () {
                        return window.pdfjsLib.getDocument({ url: url }).promise;
                    })
                    .then(function (doc) {
                        pdfState.doc = doc;
                        pdfState.page = 1;
                        pdfState.total = doc.numPages;
                        pdfState.zoom = 1;
                        updatePdfControls();
                        renderPdfPage();
                    })
                    .catch(function (error) {
                        console.error('Galat saat membuka PDF:', error);
                        showMessage('Pratinjau PDF tidak dapat dimuat. Gunakan tombol unduh di bawah.', 'warning');
                    });
            }

            function openImage(url) {
                hideAll();
                imageWrapper.classList.remove('d-none');
                imageEl.src = url;
                imageEl.onload = function () {
                    stopLoading();
                };
                imageEl.onerror = function () {
                    showMessage('Pratinjau gambar tidak dapat dimuat. Gunakan tombol unduh di bawah.', 'warning');
                };
            }

            function openDocx(url) {
                showLoading();
                docxWrapper.innerHTML = '';

                ensureMammoth()
                    .then(function () {
                        return fetch(url);
                    })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Gagal mengambil dokumen.');
                        }
                        return response.arrayBuffer();
                    })
                    .then(function (arrayBuffer) {
                        return window.mammoth.convertToHtml({ arrayBuffer: arrayBuffer }, {
                            transformDocument: mammoth.transforms.paragraph(function (element) {
                                element.alignment = element.alignment || 'left';
                                return element;
                            }),
                        });
                    })
                    .then(function (result) {
                        stopLoading();
                        docxWrapper.innerHTML = '<div class="docx-content">' + result.value + '</div>';
                        docxWrapper.classList.remove('d-none');
                    })
                    .catch(function (error) {
                        console.error('Galat saat memuat DOCX:', error);
                        showMessage('Pratinjau dokumen tidak dapat dimuat. Gunakan tombol unduh di bawah.', 'warning');
                    });
            }

            function getFileNameFromUrl(url) {
                if (!url) {
                    return 'unduhan';
                }

                try {
                    const decoded = decodeURIComponent(url.split('?')[0]);
                    const parts = decoded.split('/');
                    return parts[parts.length - 1] || 'unduhan';
                } catch (error) {
                    return 'unduhan';
                }
            }

            function openPreview(url, type, fileName) {
                currentUrl = url;
                currentFileName = fileName || getFileNameFromUrl(url);
                downloadButton.href = url;
                downloadButton.setAttribute('download', currentFileName);

                if (!url) {
                    showMessage('URL file tidak ditemukan.', 'danger');
                    return;
                }

                const extension = (type || url.split('.').pop() || '').toLowerCase();

                if (imageExtensions.includes(extension)) {
                    openImage(url);
                } else if (extension === pdfExtension) {
                    openPdf(url);
                } else if (officeExtensions.includes(extension)) {
                    openDocx(url);
                } else {
                    showMessage('Pratinjau tidak tersedia. Gunakan tombol unduh di bawah.', 'info');
                }
            }

            document.body.addEventListener('click', function (event) {
                const button = event.target.closest('.btn-preview-file');
                if (!button) {
                    return;
                }

                event.preventDefault();

                const url = button.getAttribute('data-file-url');
                const type = button.getAttribute('data-file-type');
                const fileName = button.getAttribute('data-file-name');

                showLoading();
                openPreview(url, type, fileName);
                modalInstance.show();
            });

            if (pdfPrevBtn) {
                pdfPrevBtn.addEventListener('click', function () {
                    if (!pdfState.doc || pdfState.page <= 1) {
                        return;
                    }
                    pdfState.page -= 1;
                    renderPdfPage();
                });
            }

            if (pdfNextBtn) {
                pdfNextBtn.addEventListener('click', function () {
                    if (!pdfState.doc || pdfState.page >= pdfState.total) {
                        return;
                    }
                    pdfState.page += 1;
                    renderPdfPage();
                });
            }

            if (pdfZoomInBtn) {
                pdfZoomInBtn.addEventListener('click', function () {
                    if (!pdfState.doc || pdfState.zoom >= 2.4) {
                        return;
                    }
                    pdfState.zoom = Math.min(2.4, pdfState.zoom + 0.2);
                    renderPdfPage();
                });
            }

            if (pdfZoomOutBtn) {
                pdfZoomOutBtn.addEventListener('click', function () {
                    if (!pdfState.doc || pdfState.zoom <= 0.6) {
                        return;
                    }
                    pdfState.zoom = Math.max(0.6, pdfState.zoom - 0.2);
                    renderPdfPage();
                });
            }

            if (pdfZoomResetBtn) {
                pdfZoomResetBtn.addEventListener('click', function () {
                    if (!pdfState.doc) {
                        return;
                    }
                    pdfState.zoom = 1;
                    renderPdfPage();
                });
            }

            modalEl.addEventListener('hidden.bs.modal', function () {
                hideAll();
                resetPdfState();
                if (imageEl) {
                    imageEl.removeAttribute('src');
                }
                if (docxWrapper) {
                    docxWrapper.innerHTML = '';
                }
            });
        });
    </script>
@endonce
