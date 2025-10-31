import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const getCsrfToken = () => {
    if (typeof document === 'undefined') {
        return '';
    }

    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
};

Alpine.data('learnFilters', ({ initialSearch = '' } = {}) => ({
    search: initialSearch,
    debounceHandle: null,
    queueSearch(value) {
        this.search = value;
        if (this.debounceHandle) {
            clearTimeout(this.debounceHandle);
        }
        this.debounceHandle = setTimeout(() => {
            this.$refs.filterForm?.requestSubmit();
        }, 350);
    },
    clearSearch() {
        if (!this.search) {
            return;
        }
        this.search = '';
        if (this.debounceHandle) {
            clearTimeout(this.debounceHandle);
        }
        this.$nextTick(() => {
            this.$refs.filterForm?.requestSubmit();
        });
    },
}));

Alpine.data('noteDisplay', ({ text = '', sections = {}, examples = [], delay = 220 } = {}) => ({
    loading: true,
    text,
    sections,
    examples,
    labels: {
        core_concept: 'Core concept',
        context: 'Context',
        real_world: 'Real-world context',
    },
    init() {
        setTimeout(() => {
            this.loading = false;
        }, delay);
    },
    hasText() {
        return (this.text ?? '').trim().length > 0;
    },
    hasStructured() {
        return Object.keys(this.sections ?? {}).length > 0 || (this.examples ?? []).length > 0;
    },
    sectionLabel(key) {
        return this.labels[key] ?? (key ?? '').replace(/[_-]+/g, ' ').replace(/\b\w/g, (s) => s.toUpperCase());
    },
}));

Alpine.data('noteFeedback', ({ endpoint, payload = {}, delay = 350 } = {}) => ({
    status: 'idle',
    message: '',
    timer: null,
    submit(helpful) {
        if (!endpoint) {
            return;
        }

        if (this.timer) {
            clearTimeout(this.timer);
        }

        this.status = 'waiting';
        this.message = '';

        this.timer = setTimeout(() => {
            this.status = 'submitting';

            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                body: JSON.stringify({
                    ...payload,
                    helpful: Boolean(helpful),
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(response.statusText);
                    }
                    return response.json().catch(() => ({}));
                })
                .then((data) => {
                    this.status = 'success';
                    this.message = data.message ?? 'Thanks for your feedback!';
                    setTimeout(() => {
                        this.status = 'idle';
                        this.message = '';
                    }, 2500);
                })
                .catch(() => {
                    this.status = 'error';
                    this.message = 'Unable to save feedback right now.';
                });
        }, delay);
    },
}));

Alpine.data('skeletonLoader', ({ delay = 200 } = {}) => ({
    loading: true,
    init() {
        setTimeout(() => {
            this.loading = false;
        }, delay);
    },
}));

Alpine.start();
