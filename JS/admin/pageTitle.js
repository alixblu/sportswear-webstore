function createPageTitle(newTitle, showDownloadBtn, showAddBtn) {
    return `
      <div class="page-title">
        <div class="title">${newTitle}</div>
        <div class="action-buttons">
          ${showDownloadBtn ? `
            <button id="downloadBtn" class="btn btn-outline">
              <i class="fas fa-download"></i> Export
            </button>` : ""}
          ${showAddBtn ? `
            <button id="addBtn" class="btn btn-primary">
              <i class="fas fa-plus"></i> Add New
            </button>` : ""}
        </div>
      </div>
    `;
  }
  