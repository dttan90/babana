var dhxwindow;
var grid;

function orderGrid() {
    // creating DHTMLX Grid 
    grid = new dhx.Grid("grid_container", {
        columns: [

            { width: 50, id: "bill_id", header: [{ text: "Id" }] },
            { width: 150, id: "table_order_name", header: [{ text: "Bàn" }] },
            { width: 150, id: "date_check_in", header: [{ text: "Giờ Vào" }] },
            { width: 150, id: "date_check_out", header: [{ text: "Giờ Ra" }] },
            { width: 150, id: "total", header: [{ text: "Tổng" }] },
            { width: 150, id: "status", header: [{ text: "Trạng Thái" }] },
            { width: 150, id: "area_name", header: [{ text: "Khu Vực" }] },
            { id: "promotion_description", header: [{ text: "Khuyến Mãi" }] },
            { id: "note", header: [{ text: "Ghi Chú" }] },
            { width: 50, id: "check", header: [{ text: "Chọn" }], type: "boolean" }
        ],
        editable: true,
        autoWidth: true,
        // adjust: true
        // data: dataset,
    });

    grid.data.load("./load").then(function () {
        // some logic here
    });
}


function windows(id) {
    if (id == 'description') {
        const windowHtml = "<p>Here is a neat and flexible JavaScript window system with a fast and simple initialization.</p><p>Inspect all the DHTMLX window samples to discover each and every feature.</p><img style='display: block; width: 200px; height: 200px; margin-top: 20px; margin-left: auto; margin-right: auto' src='https://snippet.dhtmlx.com/codebase/data/common/img/01/developer-01.svg'>";
        dhxwindow = new dhx.Window({
            width: 360,
            height: 520,
            closable: true,
            movable: true,
            modal: true,
            title: "Window",
            html: windowHtml
        });
    }

    dhxwindow.show();
}


/*
    |
    | running
    |
*/

orderGrid();
