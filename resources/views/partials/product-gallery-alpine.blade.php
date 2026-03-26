<script>
function createImageManager() {
    return {
        files: [],
        primaryIdx: 0,
        dragIdx: null,
        dropHover: false,
        init() {
            var self = this;
            var form = this.$el.closest('form');
            if (form) {
                form.addEventListener('submit', function () { self.syncInput(); });
            }
        },
        onFilesSelected(e) { var fl = Array.from(e.target.files); e.target.value = ''; this.addFiles(fl); },
        onDropFiles(e) { this.addFiles(Array.from(e.dataTransfer.files)); },
        addFiles(fileList) {
            var self = this;
            fileList.forEach(function (f) {
                if (!f.type.startsWith('image/')) return;
                var idx = self.files.length;
                self.files.push({ name: f.name, file: f, preview: '' });
                var reader = new FileReader();
                reader.onload = function (ev) { self.files[idx].preview = ev.target.result; };
                reader.readAsDataURL(f);
            });
            this.syncInput();
        },
        removeFile(idx) {
            this.files.splice(idx, 1);
            if (this.primaryIdx >= this.files.length) this.primaryIdx = Math.max(0, this.files.length - 1);
            this.syncInput();
        },
        onDrop(toIdx) {
            if (this.dragIdx === null || this.dragIdx === toIdx) return;
            var wasPrimary = this.dragIdx === this.primaryIdx;
            var moved = this.files.splice(this.dragIdx, 1)[0];
            this.files.splice(toIdx, 0, moved);
            if (wasPrimary) this.primaryIdx = toIdx;
            this.dragIdx = null;
            this.syncInput();
        },
        syncInput() {
            var dt = new DataTransfer();
            this.files.forEach(function (f) { dt.items.add(f.file); });
            this.$refs.fileInput.files = dt.files;
        }
    };
}

/** Same behavior as admin product image manager; vendor-themed markup lives in Blade. */
function vendorProductGalleryEditor(imgData) {
    var data = imgData || [];
    var primary = null;
    data.forEach(function (i) { if (i.is_primary) primary = i.id; });
    return {
        existing: data.map(function (i) { return { id: i.id, url: i.url, removed: false }; }),
        primaryId: primary,
        newFiles: [],
        dragIdx: null,
        dropHover: false,
        fileCounter: 0,
        init() {
            var self = this;
            var form = this.$el.closest('form');
            if (form) {
                form.addEventListener('submit', function () { self.syncNativeInput(); });
            }
        },
        totalImgCount() {
            return this.existing.filter(function (i) { return !i.removed; }).length + this.newFiles.length;
        },
        toggleRemove(img) {
            img.removed = !img.removed;
            if (img.removed && this.primaryId == img.id) {
                var next = this.existing.find(function (i) { return !i.removed; });
                this.primaryId = next ? next.id : null;
            }
        },
        onDropExisting(toIdx) {
            if (this.dragIdx === null || this.dragIdx === toIdx) return;
            var moved = this.existing.splice(this.dragIdx, 1)[0];
            this.existing.splice(toIdx, 0, moved);
            this.dragIdx = null;
        },
        onFilesSelected(e) {
            var fl = Array.from(e.target.files);
            e.target.value = '';
            this.addFiles(fl);
        },
        onDropFiles(e) {
            this.addFiles(Array.from(e.dataTransfer.files));
        },
        addFiles(fileList) {
            var self = this;
            fileList.forEach(function (f) {
                if (!f.type.startsWith('image/')) return;
                var id = 'new-' + (++self.fileCounter);
                var sz = f.size < 1048576 ? (f.size / 1024).toFixed(0) + ' KB' : (f.size / 1048576).toFixed(1) + ' MB';
                var idx = self.newFiles.length;
                self.newFiles.push({ id: id, name: f.name, file: f, preview: '', size: sz });
                var reader = new FileReader();
                reader.onload = function (ev) { self.newFiles[idx].preview = ev.target.result; };
                reader.readAsDataURL(f);
            });
            this.syncNativeInput();
        },
        removeNewFile(idx) {
            this.newFiles.splice(idx, 1);
            this.syncNativeInput();
        },
        syncNativeInput() {
            var dt = new DataTransfer();
            this.newFiles.forEach(function (nf) { dt.items.add(nf.file); });
            this.$refs.fileInput.files = dt.files;
        }
    };
}
</script>
