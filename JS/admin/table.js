
//   inside <div class="table-card">

function generateTable(iconClass, tableName, columns, data) {
    console.log('table.js is loaded');

    let tableHTML = `
        <div class="card-title">
            <h3><i class="${iconClass}"></i> ${tableName}</h3>
            <button class="btn btn-outline btn-sm">
                <i class="fas fa-eye"></i> View All
            </button>
        </div>
        <table class="data-table">
            <thead>
                <tr>`;
    
    // Generate the column headers
    columns.forEach(column => {
        tableHTML += `<th>${column}</th>`;
    });

    tableHTML += `
                </tr>
            </thead>
            <tbody>`;
    
    // Generate the table rows
    data.forEach(row => {
        tableHTML += `<tr>`;
        row.forEach(cell => {
            tableHTML += `<td>${cell}</td>`;
        });
        tableHTML += `</tr>`;
    });

    tableHTML += `
            </tbody>
        </table>`;
    
    return tableHTML;
}


