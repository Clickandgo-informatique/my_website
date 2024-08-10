const tbody = document.getElementsByTagName("tbody")[0];
const rows = tbody.rows;

for (let i = 0; i < rows.length; i++) {
  var row = rows[i];

  //   row.setAttribute("draggable", true);
  row.addEventListener("dragstart", (event) => {
    row = event.target;
    row.addEventListener("dragover", (event) => {
      let e = event;
      e.preventDefault();

      let allRows = Array.from(rows);
      if (allRows.indexOf(e.target.parentNode) > allRows.indexOf(row)) {
        e.target.parentNode.after(row);
      } else {
        e.target.parentNode.before(row);
      }
    });
  });
}
