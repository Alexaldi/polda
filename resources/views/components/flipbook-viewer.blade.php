@once
    <div class="modal fade" id="flipbookModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content" data-flipbook-modal>
                <div class="modal-header border-0">
                    <h5 class="modal-title">Pratinjau Dokumen (Flipbook)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0 position-relative">
                    <div class="d-flex justify-content-center align-items-center py-5" data-flipbook-loading>
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Memuat...</span>
                        </div>
                    </div>
                    <div class="flipbook-container-wrapper position-relative w-100" data-flipbook-stage>
                        <div id="flipbookContainer" class="w-100 h-100" data-flipbook-container></div>
                    </div>
                    <div class="p-4 d-none" data-flipbook-message></div>
                </div>
                <div class="modal-footer border-0 d-flex flex-wrap gap-2 justify-content-between">
                    <div class="btn-group" role="group" aria-label="Kontrol zoom">
                        <button type="button" class="btn btn-outline-primary" data-flipbook-zoom-out title="Perkecil">
                            <i class="fa fa-search-minus"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-flipbook-zoom-reset title="Reset">
                            <i class="fa fa-compress"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" data-flipbook-zoom-in title="Perbesar">
                            <i class="fa fa-search-plus"></i>
                        </button>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a
                            href="#"
                            class="btn btn-primary"
                            target="_blank"
                            rel="noopener"
                            download
                            data-flipbook-download
                        >
                            <i class="fa fa-download me-1"></i> Unduh File
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #flipbookModal .modal-content {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        #flipbookModal .modal-body {
            background-color: var(--bs-body-bg);
            min-height: 320px;
        }

        #flipbookModal [data-flipbook-stage] {
            height: 80vh;
            max-height: 860px;
            overflow: hidden;
        }

        #flipbookModal [data-flipbook-container] {
            width: 100%;
            height: 100%;
            position: relative;
            margin: 0 auto;
        }

        #flipbookModal [data-flipbook-container].flipbook-ready {
            box-shadow: 0 1rem 3rem rgba(15, 23, 42, 0.35);
            background: transparent;
        }

        #flipbookModal .flipbook-page {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
        }

        #flipbookModal .flipbook-page canvas {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        #flipbookModal [data-flipbook-message] .alert {
            border: 0;
            border-radius: 0.75rem;
            background-color: rgba(15, 23, 42, 0.08);
            color: #0f172a;
        }

        body.dark-version #flipbookModal .modal-content,
        body[data-bs-theme="dark"] #flipbookModal .modal-content,
        body.dark-version #flipbookModal .modal-body,
        body[data-bs-theme="dark"] #flipbookModal .modal-body {
            background-color: #0d1117;
            color: #f1f5f9;
        }

        body.dark-version #flipbookModal .btn-close,
        body[data-bs-theme="dark"] #flipbookModal .btn-close {
            filter: invert(1) grayscale(100%);
        }

        body.dark-version #flipbookModal [data-flipbook-message] .alert,
        body[data-bs-theme="dark"] #flipbookModal [data-flipbook-message] .alert {
            background-color: rgba(255, 255, 255, 0.1);
            color: #f1f5f9;
        }

        body.dark-version #flipbookModal .flipbook-page {
            background-color: #ffffff;
        }

        @media (max-width: 768px) {
            #flipbookModal [data-flipbook-stage] {
                height: 70vh;
            }
        }
    </style>
@endonce

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('flipbookModal');
            if (!modalEl) {
                return;
            }

            const container = modalEl.querySelector('[data-flipbook-container]');
            const loadingWrapper = modalEl.querySelector('[data-flipbook-loading]');
            const messageWrapper = modalEl.querySelector('[data-flipbook-message]');
            const downloadButton = modalEl.querySelector('[data-flipbook-download]');
            const zoomInBtn = modalEl.querySelector('[data-flipbook-zoom-in]');
            const zoomOutBtn = modalEl.querySelector('[data-flipbook-zoom-out]');
            const zoomResetBtn = modalEl.querySelector('[data-flipbook-zoom-reset]');
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            const modalContent = modalEl.querySelector('[data-flipbook-modal]');

            const PDF_JS_CDN = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.2.67/build/pdf.min.js';
            const PDF_WORKER_CDN = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@4.2.67/build/pdf.worker.min.js';
            const TURN_JS_CDN = 'https://cdn.jsdelivr.net/npm/turn.js@4/turn.min.js';

            let currentUrl = null;
            let currentScale = 1;
            let libraryPromise = null;
            let currentTask = null;
            let loadTimeout = null;
            let lastProgressAt = null;

            function isDarkMode() {
                return (
                    document.body.classList.contains('dark-version') ||
                    document.body.getAttribute('data-bs-theme') === 'dark'
                );
            }

            function applyTheme() {
                if (!modalContent) {
                    return;
                }

                modalContent.classList.toggle('theme-dark', isDarkMode());
            }

            applyTheme();

            const themeObserver = new MutationObserver(applyTheme);
            themeObserver.observe(document.body, {
                attributes: true,
                attributeFilter: ['class', 'data-bs-theme'],
            });

            function loadScriptOnce(src) {
                return new Promise(function (resolve, reject) {
                    let script = document.querySelector('script[data-dynamic-src="' + src + '"]');

                    if (script) {
                        if (script.getAttribute('data-loaded') === 'true') {
                            resolve();
                            return;
                        }

                        script.addEventListener('load', function () {
                            resolve();
                        }, { once: true });

                        script.addEventListener(
                            'error',
                            function () {
                                reject(new Error('Gagal memuat skrip: ' + src));
                            },
                            { once: true }
                        );

                        return;
                    }

                    script = document.createElement('script');
                    script.src = src;
                    script.async = true;
                    script.setAttribute('data-dynamic-src', src);
                    script.addEventListener('load', function () {
                        script.setAttribute('data-loaded', 'true');
                        resolve();
                    }, { once: true });
                    script.addEventListener(
                        'error',
                        function () {
                            script.remove();
                            reject(new Error('Gagal memuat skrip: ' + src));
                        },
                        { once: true }
                    );

                    document.head.appendChild(script);
                });
            }

            function ensureLibraries() {
                if (!window.jQuery) {
                    return Promise.reject(new Error('jQuery tidak ditemukan. Pastikan sudah dimuat dari layout.'));
                }

                if (!libraryPromise) {
                    libraryPromise = loadScriptOnce(PDF_JS_CDN)
                        .then(function () {
                            if (!window.pdfjsLib) {
                                throw new Error('pdf.js tidak tersedia setelah dimuat.');
                            }

                            window.pdfjsLib.GlobalWorkerOptions.workerSrc = PDF_WORKER_CDN;
                        })
                        .then(function () {
                            return loadScriptOnce(TURN_JS_CDN);
                        })
                        .then(function () {
                            if (!window.jQuery.fn || typeof window.jQuery.fn.turn !== 'function') {
                                throw new Error('Turn.js tidak tersedia.');
                            }
                        })
                        .catch(function (error) {
                            console.error('Gagal memuat library flipbook:', error);
                            libraryPromise = null;
                            throw error;
                        });
                }

                return libraryPromise;
            }

            function resolveUrl(url) {
                if (!url) {
                    return '';
                }

                try {
                    return new URL(url, window.location.origin).href;
                } catch (error) {
                    return url;
                }
            }

            function resetLoadTimeout() {
                if (loadTimeout) {
                    window.clearTimeout(loadTimeout);
                }

                loadTimeout = window.setTimeout(function () {
                    const tooLong = !lastProgressAt || Date.now() - lastProgressAt > 12000;
                    if (tooLong) {
                        if (currentTask && typeof currentTask.destroy === 'function') {
                            currentTask.destroy().catch(function () {
                                // Ignore destroy errors
                            });
                        }

                        showMessage('Mode flipbook memerlukan waktu terlalu lama. Gunakan tombol unduh untuk membuka file.', 'warning');
                        window.dispatchEvent(new CustomEvent('report-flipbook:failed', { detail: { url: currentUrl } }));
                    } else {
                        resetLoadTimeout();
                    }
                }, 15000);
            }

            function clearLoadTimeout() {
                if (loadTimeout) {
                    window.clearTimeout(loadTimeout);
                    loadTimeout = null;
                }
            }

            function resetView() {
                clearLoadTimeout();

                if (messageWrapper) {
                    messageWrapper.classList.add('d-none');
                    messageWrapper.innerHTML = '';
                }

                if (downloadButton) {
                    downloadButton.href = '#';
                }

                if (container) {
                    if (window.jQuery && window.jQuery(container).data('turn')) {
                        window.jQuery(container).turn('destroy');
                    }

                    container.innerHTML = '';
                    container.classList.remove('flipbook-ready');
                    container.style.transform = 'scale(1)';
                    container.style.transformOrigin = 'center center';
                }

                if (currentTask && typeof currentTask.destroy === 'function') {
                    currentTask.destroy().catch(function () {
                        // ignore
                    });
                }

                currentTask = null;
                currentScale = 1;
                lastProgressAt = null;

                if (loadingWrapper) {
                    loadingWrapper.classList.remove('d-none');
                }
            }

            function showLoading() {
                if (loadingWrapper) {
                    loadingWrapper.classList.remove('d-none');
                }
            }

            function hideLoading() {
                if (loadingWrapper) {
                    loadingWrapper.classList.add('d-none');
                }
            }

            function showMessage(message, type) {
                hideLoading();

                if (!messageWrapper) {
                    return;
                }

                const isDark = isDarkMode();
                const typeClass =
                    type === 'danger'
                        ? 'alert-danger'
                        : type === 'warning'
                        ? 'alert-warning'
                        : 'alert-info';

                const manualLink = currentUrl
                    ? `<a href="${currentUrl}" target="_blank" rel="noopener" class="btn btn-link btn-sm px-0">Gunakan tombol unduh</a>`
                    : '';

                messageWrapper.innerHTML = `
                    <div class="alert ${typeClass} mb-2" role="alert">${message}</div>
                    <div class="small text-center ${isDark ? 'text-light' : 'text-muted'}">${manualLink}</div>
                `;

                messageWrapper.classList.remove('d-none');
            }

            function ensureDownload(url) {
                if (!downloadButton) {
                    return;
                }

                downloadButton.href = url;
            }

            function scheduleProgressUpdate() {
                lastProgressAt = Date.now();
                resetLoadTimeout();
            }

            function computeScale(viewport) {
                const stage = modalEl.querySelector('[data-flipbook-stage]');
                if (!stage) {
                    return 1.2;
                }

                const stageWidth = stage.clientWidth || 960;
                const stageHeight = stage.clientHeight || 640;
                const widthScale = stageWidth / viewport.width;
                const heightScale = stageHeight / viewport.height;
                const baseScale = Math.min(widthScale, heightScale) * 0.95;
                return Math.max(baseScale, 0.7);
            }

            function buildFlipbook(pdf) {
                return new Promise(function (resolve, reject) {
                    const totalPages = pdf.numPages;
                    const fragment = document.createDocumentFragment();

                    (async function renderSequential(index) {
                        if (index > totalPages) {
                            container.innerHTML = '';
                            container.appendChild(fragment);
                            resolve();
                            return;
                        }

                        try {
                            const page = await pdf.getPage(index);
                            const base = page.getViewport({ scale: 1 });
                            const viewport = page.getViewport({ scale: computeScale(base) });
                            const canvas = document.createElement('canvas');
                            const context = canvas.getContext('2d', { alpha: false });
                            canvas.width = viewport.width;
                            canvas.height = viewport.height;

                            await page.render({ canvasContext: context, viewport: viewport }).promise;
                            page.cleanup();

                            const wrapper = document.createElement('div');
                            wrapper.className = 'flipbook-page';
                            wrapper.appendChild(canvas);
                            fragment.appendChild(wrapper);

                            scheduleProgressUpdate();
                            renderSequential(index + 1);
                        } catch (error) {
                            reject(error);
                            return;
                        }
                    })(1);
                });
            }

            function initializeTurn(viewport) {
                const $container = window.jQuery(container);

                if ($container.data('turn')) {
                    $container.turn('destroy');
                }

                const isSmall = window.matchMedia('(max-width: 992px)').matches;
                const pageWidth = viewport.width;
                const pageHeight = viewport.height;

                $container.turn({
                    width: isSmall ? pageWidth : pageWidth * 2,
                    height: pageHeight,
                    autoCenter: true,
                    display: isSmall ? 'single' : 'double',
                    gradients: true,
                    elevation: 70,
                    duration: 900,
                    when: {
                        turning: function () {
                            container.classList.add('turning');
                        },
                        turned: function () {
                            container.classList.remove('turning');
                        },
                    },
                });

                container.classList.add('flipbook-ready');
            }

            function updateTurnSize(viewport) {
                const $container = window.jQuery(container);
                if (!$container.data('turn')) {
                    return;
                }

                const isSmall = window.matchMedia('(max-width: 992px)').matches;
                const width = isSmall ? viewport.width : viewport.width * 2;
                $container.turn('size', width, viewport.height);
                $container.turn('display', isSmall ? 'single' : 'double');
            }

            function setScale(scale) {
                currentScale = Math.min(Math.max(scale, 0.5), 3);
                container.style.transform = 'scale(' + currentScale + ')';
            }

            function zoomIn() {
                setScale(currentScale + 0.2);
            }

            function zoomOut() {
                setScale(currentScale - 0.2);
            }

            function zoomReset() {
                setScale(1);
            }

            async function renderFlipbook(url) {
                if (!window.pdfjsLib) {
                    throw new Error('pdf.js belum siap.');
                }

                currentTask = window.pdfjsLib.getDocument({
                    url: url,
                    withCredentials: false,
                    disableAutoFetch: false,
                    disableStream: false,
                });

                currentTask.onProgress = function () {
                    scheduleProgressUpdate();
                };

                const pdf = await currentTask.promise;
                const firstPage = await pdf.getPage(1);
                const baseViewport = firstPage.getViewport({ scale: 1 });
                firstPage.cleanup();

                await buildFlipbook(pdf);

                hideLoading();

                if (!container.firstElementChild) {
                    throw new Error('Tidak ada halaman yang dirender.');
                }

                const sampleCanvas = container.querySelector('.flipbook-page canvas');
                const viewport = {
                    width: sampleCanvas ? sampleCanvas.width : baseViewport.width,
                    height: sampleCanvas ? sampleCanvas.height : baseViewport.height,
                };

                initializeTurn(viewport);
                updateTurnSize(viewport);
                clearLoadTimeout();
                window.dispatchEvent(new CustomEvent('report-flipbook:opened', { detail: { url: url } }));

                if (currentTask && typeof currentTask.destroy === 'function') {
                    currentTask.destroy().catch(function () {
                        // ignore cleanup errors
                    });
                }

                currentTask = null;
            }

            window.ReportFlipbook = window.ReportFlipbook || {};
            window.ReportFlipbook.open = function (url) {
                const resolvedUrl = resolveUrl(url);
                currentUrl = resolvedUrl;

                resetView();

                if (!resolvedUrl) {
                    showMessage('File PDF tidak ditemukan.', 'warning');
                    window.dispatchEvent(new CustomEvent('report-flipbook:failed', { detail: { url: resolvedUrl } }));
                    return;
                }

                ensureDownload(resolvedUrl);
                showLoading();
                applyTheme();
                modalInstance.show();

                ensureLibraries()
                    .then(function () {
                        scheduleProgressUpdate();
                        return renderFlipbook(resolvedUrl);
                    })
                    .catch(function (error) {
                        console.error('Flipbook gagal dimuat:', error);
                        showMessage('Mode flipbook tidak dapat dimuat. Gunakan tombol unduh untuk membuka file.', 'danger');
                        clearLoadTimeout();
                        if (currentTask && typeof currentTask.destroy === 'function') {
                            currentTask.destroy().catch(function () {
                                // ignore cleanup errors
                            });
                        }
                        currentTask = null;
                        window.dispatchEvent(new CustomEvent('report-flipbook:failed', { detail: { url: resolvedUrl } }));
                    });

                resetLoadTimeout();
            };

            modalEl.addEventListener('shown.bs.modal', function () {
                if (container && container.classList.contains('flipbook-ready')) {
                    const sampleCanvas = container.querySelector('.flipbook-page canvas');
                    if (sampleCanvas) {
                        updateTurnSize({ width: sampleCanvas.width, height: sampleCanvas.height });
                    }
                }
            });

            modalEl.addEventListener('hidden.bs.modal', function () {
                if (window.jQuery && window.jQuery(container).data('turn')) {
                    window.jQuery(container).turn('destroy');
                }

                if (currentTask && typeof currentTask.destroy === 'function') {
                    currentTask.destroy().catch(function () {
                        // ignore
                    });
                }

                currentTask = null;
                clearLoadTimeout();
                currentUrl = null;
                resetView();
            });

            if (zoomInBtn) {
                zoomInBtn.addEventListener('click', function () {
                    zoomIn();
                });
            }

            if (zoomOutBtn) {
                zoomOutBtn.addEventListener('click', function () {
                    zoomOut();
                });
            }

            if (zoomResetBtn) {
                zoomResetBtn.addEventListener('click', function () {
                    zoomReset();
                });
            }

            window.addEventListener('resize', function () {
                if (!container || !container.classList.contains('flipbook-ready')) {
                    return;
                }

                const sampleCanvas = container.querySelector('.flipbook-page canvas');
                if (!sampleCanvas) {
                    return;
                }

                updateTurnSize({ width: sampleCanvas.width, height: sampleCanvas.height });
            });
        });
    </script>
@endonce
