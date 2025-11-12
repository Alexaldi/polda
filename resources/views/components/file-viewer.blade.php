<div class="modal fade" id="fileViewerModal" tabindex="-1" aria-labelledby="fileViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content" data-viewer-modal>
            <div class="modal-header border-0">
                <h5 class="modal-title" id="fileViewerModalLabel">Pratinjau Bukti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body position-relative">
                <div class="viewer-loading d-flex justify-content-center align-items-center py-5" data-viewer-loading>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>
                </div>
                <div class="ratio ratio-16x9 d-none" data-viewer-frame>
                    <iframe
                        src="about:blank"
                        title="File preview"
                        frameborder="0"
                        allowfullscreen
                        allow="cross-origin"
                        class="rounded shadow-sm"
                        referrerpolicy="no-referrer"
                        data-viewer-iframe
                    ></iframe>
                </div>
                <div class="viewer-image-wrapper d-none" data-viewer-image>
                    <img
                        src=""
                        alt="Pratinjau file"
                        class="img-fluid rounded shadow"
                        referrerpolicy="no-referrer"
                        data-viewer-image-el
                    />
                </div>
                <div class="viewer-message d-none" data-viewer-message></div>
            </div>
            <div class="modal-footer border-0 d-flex justify-content-between flex-wrap gap-2">
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-outline-primary d-none" data-open-flipbook>
                        <i class="fa fa-book-open me-1"></i> Mode Flipbook
                    </button>
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
                </div>
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
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        #fileViewerModal .modal-header,
        #fileViewerModal .modal-footer {
            background-color: transparent;
        }

        #fileViewerModal .viewer-loading {
            min-height: 220px;
        }

        #fileViewerModal .ratio {
            transition: opacity 0.2s ease;
        }

        #fileViewerModal [data-viewer-frame] iframe {
            width: 100%;
            height: 100%;
            background-color: var(--bs-body-bg);
        }

        #fileViewerModal .viewer-image-wrapper {
            background-color: var(--bs-body-bg);
            padding: 1.25rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 220px;
        }

        #fileViewerModal .viewer-message {
            min-height: 120px;
        }

        #fileViewerModal .viewer-message .alert {
            border: 0;
            border-radius: 0.75rem;
            background-color: rgba(15, 23, 42, 0.08);
            color: #0f172a;
        }

        body.dark-version #fileViewerModal .modal-content,
        body[data-bs-theme="dark"] #fileViewerModal .modal-content {
            background-color: #0d1117;
            color: #f1f5f9;
        }

        body.dark-version #fileViewerModal .btn-close,
        body[data-bs-theme="dark"] #fileViewerModal .btn-close {
            filter: invert(1) grayscale(100%);
        }

        body.dark-version #fileViewerModal [data-viewer-frame] iframe,
        body[data-bs-theme="dark"] #fileViewerModal [data-viewer-frame] iframe,
        body.dark-version #fileViewerModal .viewer-image-wrapper,
        body[data-bs-theme="dark"] #fileViewerModal .viewer-image-wrapper {
            background-color: #0d1117;
        }

        body.dark-version #fileViewerModal .viewer-message .alert,
        body[data-bs-theme="dark"] #fileViewerModal .viewer-message .alert {
            background-color: rgba(255, 255, 255, 0.1);
            color: #f1f5f9;
        }

        body.dark-version #fileViewerModal .modal-footer,
        body[data-bs-theme="dark"] #fileViewerModal .modal-footer {
            border-top-color: rgba(148, 163, 184, 0.2);
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
            const frameWrapper = modalEl.querySelector('[data-viewer-frame]');
            const iframeEl = modalEl.querySelector('[data-viewer-iframe]');
            const imageWrapper = modalEl.querySelector('[data-viewer-image]');
            const imageEl = modalEl.querySelector('[data-viewer-image-el]');
            const messageWrapper = modalEl.querySelector('[data-viewer-message]');
            const downloadButton = modalEl.querySelector('[data-download-button]');
            const flipbookButton = modalEl.querySelector('[data-open-flipbook]');
            const modalContent = modalEl.querySelector('[data-viewer-modal]');

            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            const officeExtensions = ['doc', 'docx'];
            const pdfExtension = 'pdf';

            const GOOGLE_VIEWER = 'https://docs.google.com/gview?embedded=true&url=';
            const PDF_VIEWER = 'https://mozilla.github.io/pdf.js/web/viewer.html?file=';

            let currentUrl = null;
            let frameTimeout = null;
            let availabilityAbort = null;
            let pendingFlipbookUrl = null;

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

                const dark = isDarkMode();
                modalContent.classList.toggle('theme-dark', dark);
            }

            applyTheme();

            const themeObserver = new MutationObserver(applyTheme);
            themeObserver.observe(document.body, {
                attributes: true,
                attributeFilter: ['class', 'data-bs-theme'],
            });

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

            function cancelAvailabilityCheck() {
                if (availabilityAbort) {
                    availabilityAbort.abort();
                    availabilityAbort = null;
                }
            }

            function checkFileAvailability(url) {
                cancelAvailabilityCheck();

                if (!url) {
                    return Promise.resolve({ status: 'error', code: 0 });
                }

                availabilityAbort = new AbortController();
                const controller = availabilityAbort;
                const timeout = window.setTimeout(function () {
                    controller.abort();
                }, 8000);

                return fetch(url, {
                    method: 'HEAD',
                    mode: 'cors',
                    referrerPolicy: 'no-referrer',
                    signal: controller.signal,
                    credentials: 'omit',
                })
                    .then(function (response) {
                        window.clearTimeout(timeout);
                        availabilityAbort = null;

                        if (!response.ok) {
                            const code = response.status;
                            if ([403, 404, 502].includes(code)) {
                                return { status: 'error', code: code };
                            }
                        }

                        return { status: 'ok', code: response.status };
                    })
                    .catch(function (error) {
                        window.clearTimeout(timeout);
                        availabilityAbort = null;

                        if (error && error.name === 'AbortError') {
                            return { status: 'unknown' };
                        }

                        return { status: 'unknown' };
                    });
            }

            function resetState() {
                cancelAvailabilityCheck();

                [loadingWrapper, frameWrapper, imageWrapper, messageWrapper].forEach(function (element) {
                    if (element) {
                        element.classList.add('d-none');
                    }
                });

                if (iframeEl) {
                    iframeEl.removeAttribute('src');
                    iframeEl.setAttribute('src', 'about:blank');
                }

                if (imageEl) {
                    imageEl.src = '';
                }

                if (messageWrapper) {
                    messageWrapper.innerHTML = '';
                }

                if (downloadButton) {
                    downloadButton.href = '#';
                    downloadButton.classList.toggle('disabled', true);
                }

                if (flipbookButton) {
                    flipbookButton.classList.add('d-none');
                    flipbookButton.removeAttribute('data-file-url');
                }

                if (frameTimeout) {
                    window.clearTimeout(frameTimeout);
                    frameTimeout = null;
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
                    <div class="alert ${typeClass} text-center mb-2" role="alert">${message}</div>
                    <div class="text-center small ${isDark ? 'text-light' : 'text-muted'}">${manualLink}</div>
                `;

                messageWrapper.classList.remove('d-none');
            }

            function enableDownload(url) {
                if (!downloadButton) {
                    return;
                }

                downloadButton.href = url;
                downloadButton.classList.toggle('disabled', !url);
            }

            function prepareIframe(src, errorMessage) {
                if (!iframeEl || !frameWrapper) {
                    showMessage(errorMessage || 'Pratinjau tidak tersedia saat ini.', 'warning');
                    return;
                }

                frameWrapper.classList.remove('d-none');

                if (frameTimeout) {
                    window.clearTimeout(frameTimeout);
                }

                const clearTimer = function () {
                    if (frameTimeout) {
                        window.clearTimeout(frameTimeout);
                        frameTimeout = null;
                    }
                };

                const handleLoad = function () {
                    clearTimer();
                    hideLoading();
                };

                const handleError = function () {
                    clearTimer();
                    hideLoading();
                    showMessage(errorMessage || 'File tidak dapat dimuat, gunakan tombol unduh.', 'warning');
                };

                iframeEl.addEventListener('load', handleLoad, { once: true });
                iframeEl.addEventListener('error', handleError, { once: true });

                frameTimeout = window.setTimeout(function () {
                    iframeEl.removeEventListener('load', handleLoad);
                    iframeEl.removeEventListener('error', handleError);
                    hideLoading();
                    showMessage('Memuat file melebihi batas waktu. Gunakan tombol unduh.', 'warning');
                }, 15000);

                window.requestAnimationFrame(function () {
                    iframeEl.src = src;
                });
            }

            function showImagePreview(url) {
                if (!imageWrapper || !imageEl) {
                    showMessage('Pratinjau gambar tidak tersedia.', 'warning');
                    return;
                }

                imageWrapper.classList.remove('d-none');
                imageEl.src = '';

                const timeoutId = window.setTimeout(function () {
                    imageEl.onload = null;
                    imageEl.onerror = null;
                    hideLoading();
                    showMessage('Memuat gambar melebihi batas waktu. Gunakan tombol unduh.', 'warning');
                }, 15000);

                imageEl.onload = function () {
                    window.clearTimeout(timeoutId);
                    hideLoading();
                };

                imageEl.onerror = function () {
                    window.clearTimeout(timeoutId);
                    hideLoading();
                    showMessage('Gagal memuat pratinjau gambar. Gunakan tombol unduh.', 'danger');
                };

                imageEl.src = url;
            }

            function showPdfPreview(url) {
                const viewerUrl = PDF_VIEWER + encodeURIComponent(url);
                prepareIframe(viewerUrl, 'Pratinjau PDF tidak dapat dimuat, gunakan tombol unduh.');
            }

            function showDocxPreview(url) {
                const viewerUrl = GOOGLE_VIEWER + encodeURIComponent(url);
                prepareIframe(viewerUrl, 'Pratinjau dokumen tidak dapat dimuat, gunakan tombol unduh.');
            }

            function detectExtension(url, provided) {
                if (provided) {
                    return provided.replace('.', '').toLowerCase();
                }

                const sanitized = url.split('?')[0].split('#')[0];
                const parts = sanitized.split('.');
                return parts.length > 1 ? parts.pop().toLowerCase() : '';
            }

            function openPreview(url, extension) {
                const resolvedUrl = resolveUrl(url);
                currentUrl = resolvedUrl;

                resetState();
                showLoading();
                applyTheme();
                enableDownload(resolvedUrl);

                const normalizedExtension = (extension || '').toLowerCase();
                const autoDetectedExtension = normalizedExtension || detectExtension(resolvedUrl, normalizedExtension);

                if (flipbookButton) {
                    flipbookButton.classList.toggle('d-none', autoDetectedExtension !== pdfExtension);
                    if (autoDetectedExtension === pdfExtension) {
                        flipbookButton.setAttribute('data-file-url', resolvedUrl);
                    } else {
                        flipbookButton.removeAttribute('data-file-url');
                    }
                }

                const continuePreview = function () {
                    if (autoDetectedExtension === pdfExtension) {
                        pendingFlipbookUrl = resolvedUrl;
                        showPdfPreview(resolvedUrl);
                    } else if (imageExtensions.includes(autoDetectedExtension)) {
                        showImagePreview(resolvedUrl);
                    } else if (officeExtensions.includes(autoDetectedExtension)) {
                        showDocxPreview(resolvedUrl);
                    } else {
                        hideLoading();
                        showMessage('Pratinjau tidak tersedia untuk tipe file ini. Gunakan tombol unduh.', 'warning');
                    }
                };

                checkFileAvailability(resolvedUrl)
                    .then(function (status) {
                        if (status.status === 'error') {
                            hideLoading();
                            showMessage('File tidak dapat dimuat, gunakan tombol unduh.', 'danger');
                            return;
                        }

                        continuePreview();
                    })
                    .catch(function () {
                        continuePreview();
                    });

                modalInstance.show();
            }

            document.addEventListener('click', function (event) {
                const button = event.target.closest('.btn-preview-file');
                if (!button) {
                    return;
                }

                event.preventDefault();

                const fileUrl = button.getAttribute('data-file-url');
                if (!fileUrl) {
                    return;
                }

                const providedType = (button.getAttribute('data-file-type') || '').toLowerCase();
                const extension = detectExtension(fileUrl, providedType);

                openPreview(fileUrl, extension);
            });

            if (flipbookButton) {
                flipbookButton.addEventListener('click', function () {
                    if (!currentUrl) {
                        return;
                    }

                    if (typeof window.ReportFlipbook === 'object' && typeof window.ReportFlipbook.open === 'function') {
                        pendingFlipbookUrl = currentUrl;
                        window.ReportFlipbook.open(currentUrl);
                        modalInstance.hide();
                    } else {
                        showMessage('Mode flipbook belum siap. Gunakan pratinjau standar atau unduh file.', 'warning');
                    }
                });
            }

            window.addEventListener('report-flipbook:failed', function (event) {
                if (!pendingFlipbookUrl) {
                    return;
                }

                const failedUrl = event.detail && event.detail.url ? resolveUrl(event.detail.url) : null;
                if (!failedUrl || failedUrl !== resolveUrl(pendingFlipbookUrl)) {
                    return;
                }

                pendingFlipbookUrl = null;
                showPdfPreview(failedUrl);
                modalInstance.show();
            });

            window.addEventListener('report-flipbook:opened', function (event) {
                const openedUrl = event.detail && event.detail.url ? resolveUrl(event.detail.url) : null;
                if (!openedUrl) {
                    return;
                }

                if (pendingFlipbookUrl && resolveUrl(pendingFlipbookUrl) === openedUrl) {
                    pendingFlipbookUrl = null;
                }
            });

            modalEl.addEventListener('hidden.bs.modal', function () {
                resetState();
                currentUrl = null;
            });

            window.ReportViewer = window.ReportViewer || {};
            window.ReportViewer.open = function (fileUrl, fileType) {
                openPreview(fileUrl, fileType);
            };
            window.ReportViewer.modal = modalInstance;

            // Contoh pemanggilan:
            // window.ReportViewer.open('https://contoh.com/file.pdf', 'pdf');
        });
    </script>
@endonce
