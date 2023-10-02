"use strict";

var layout, detailLayout, addLayout;
var toolbar, detailToolbar;
var dhxWindow;
var grid, detailGrid, leftGrid, rightGrid;
var addForm;
var form;
var BDate;

function layout() {
    // Layout initialization
    layout = new dhx.Layout("layout", {
        type: "line",
        cols: [
            {
                rows: [
                    { id: "toolbar", height: "content", },
                    {
                        type: "space",
                        rows: [
                            { id: "grid", },
                        ],
                    },
                ],
            },
            {
                id: "edit-cell", css: "", hidden: true, width: 240,
                rows: [
                    { id: "edit-toolbar", height: "content", },
                    { id: "edit-form" },
                ],
            },
        ],
    });

    // attaching widgets to Layout cells
    // layout.getCell("toolbar").attach(toolbar)
    // layout.getCell("grid").attach(grid)
    // layout.getCell("edit-toolbar").attach(editToolbar)
}

function toolbar() {
    const toolbarData = [
        { type: "button", view: "flat", color: "primary", circle: true, icon: "mdi mdi-menu", },
        { id: "dashboard", value: "Dashboard", icon: "mdi mdi-view-dashboard", group: "page", twoState: true, active: true, },
        { type: "spacer", },
        { id: "add_order", type: "button", circle: true, value: "Thêm một đơn hàng (order)", size: "small", icon: "mdi mdi-plus", full: true, },
        { id: "settings2", icon: "mdi mdi-cog", type: "button", view: "link", color: "secondary", circle: true, },
        { type: "text", icon: "mdi mdi-help-circle", value: "Hướng dẫn", tooltip: "Chức năng:: Thêm một đơn hàng (order): Tạo một đơn hàng mới.", },
    ];

    // Toolbar initialization
    toolbar = new dhx.Toolbar("toolbar_container", {});
    // loading structure into Toolbar
    toolbar.data.parse(toolbarData);

    toolbar.events.on("click", function (id, e) {
        if (id == "add_order") {
            windows(id);
        } else {
            dhx.alert({
                header: "Alert Header",
                text: "Chưa có chức năng xử lý",
                buttonsAlignment: "center",
            });
        }
    });
}

function orderGrid() {
    // to get data
    $.ajax("./getOptionsOfOrderGrid")
        .done(function (data) {
            var data = JSON.parse(data);

            // console.log("data options: " + JSON.stringify(data));

            // creating DHTMLX Grid
            grid = new dhx.Grid("grid_container", {
                css: "dhx_demo-grid",
                columns: [
                    { width: 70, id: "bill_id", header: [{ text: "Mã Bill", align: "center" }], align: "center" },
                    {
                        width: 150, id: "table_order_name", header: [{ text: "Bàn" }, { content: "comboFilter" }], editorType: "combobox", editorConfig: {
                            template: ({ value }) => getOptionsTemplate(value)
                        },
                        options: data.tableOptions,
                        template: (value) => getOptionsTemplate(value),
                        htmlEnable: true
                    },
                    { width: 150, id: "date_check_in", header: [{ text: "Giờ vào", align: "center" }, { content: "comboFilter" }], align: "center", editable: false },
                    { width: 150, id: "date_check_out", header: [{ text: "Giờ ra", align: "center" }, { content: "comboFilter" }], align: "center", editable: false },
                    { width: 100, id: "total", header: [{ text: "Thành tiền", align: "center" }, { content: "comboFilter" }], align: "right", format: "#,#", editable: false },
                    // // { width: 100, id: "status", header: [{ text: "Trạng Thái", align: "center" }, {content: "comboFilter"}], align: "center" },
                    {
                        width: 150, id: "status", header: [{ text: "Trạng thái" }, { content: "comboFilter" }], editorType: "combobox", editorConfig: {
                            template: ({ value }) => getPriorityTemplate(value)
                        },
                        options: ["Đã thanh toán", "Đã giao món", "Đang đợi", "Hủy"],
                        template: (value) => getPriorityTemplate(value),
                        htmlEnable: true,
                        minWidth: 100
                    },
                    {
                        width: 150, id: "area_name", header: [{ text: "Khu vực" }, { content: "comboFilter" }], editorType: "combobox", editorConfig: {
                            template: ({ value }) => getOptionsTemplate(value)
                        },
                        options: data.areaOptions,
                        template: (value) => getOptionsTemplate(value),
                        htmlEnable: true
                    },
                    {
                        id: "promotion_description", header: [{ text: "Khuyến mãi" }, { content: "comboFilter" }], editorType: "combobox", editorConfig: {
                            template: ({ value }) => getOptionsTemplate(value)
                        },
                        options: data.promotionOptions,
                        template: (value) => getOptionsTemplate(value),
                        htmlEnable: true,
                        minWidth: 150
                    },
                    { id: "note", header: [{ text: "Ghi chú", align: "center" }, { content: "comboFilter" }], align: "left", editorType: "textarea", autoHeight: true, editable: false },
                    {
                        width: 70, id: "detail", gravity: 1.5, header: [{ text: "Chi tiết", align: "center" }], htmlEnable: true, align: "center",
                        template: function () {
                            return "<span class='action-buttons'><a class='btn btn-info detail-btn detail-button'>Xem</a></span>";
                        },
                    },
                    {
                        width: 200, id: "action", gravity: 1.5, header: [{ text: "Actions", align: "center" }], htmlEnable: true, align: "center",
                        template: function () {
                            return "<span class='action-buttons'><a class='btn btn-primary print-button'>In Hóa đơn</a><a class='btn btn-warning edit-button'>Lưu</a><a class='btn btn-danger remove-button'>Xóa</a></span>";
                        },
                    },
                ],
                editable: true,
                autoWidth: true,
                autoHeight: true,
                resizable: true,
                eventHandlers: {
                    onclick: {
                        "detail-button": function (e, data) {
                            windows("detail_order", data.row);
                        },
                        "remove-button": function (e, data) {
                            // grid.data.remove(data.row.id)
                            openEditor(data.row.id);
                        },
                        "edit-button": function (e, data) {
                            windows("edit_order", data.row);
                        },
                        "print-button": function (e, data) {
                            let selectedCell = grid.selection.getCell();
                            let bill_id = selectedCell.row.bill_id;
                            window.open("printer?bill_id=" + bill_id, "blank");
                        },
                    },
                },
                data: data.dataset
            });





            // grid.events.on("cellMouseOver", function (row, column, e) {
            //     windows('detail_order')
            // })

            grid.selection.enable();

            grid.selection.events.on("AfterSelect", function (row, col) {
                console.log("afterSelect", row, col);
            });
        })
        .fail(function () {
            alert("Không lấy được dữ liệu từ hệ thống");
        })
        .always(function () {
            console.log("Done!");
        });
}

function openEditor(id) {
    dhx.alert({
        header: "Alert Header",
        text: "Alert text",
        buttonsAlignment: "center",
    });
}

function getOptionsTemplate(value) {
    if (!value) return;
    return `
        <div class='dhx-demo_grid-template'>
            <div class='dhx-demo_grid-status'></div>
            <span>${value}</span>
        </div>
    `;
}

function getPriorityTemplate(value) {
    if (!value) return;
    let status = "dhx-demo_grid-status--not-started";
    if (value === "Đã thanh toán") status = "dhx-demo_grid-status--done";
    if (value === "Đã giao món") status = "dhx-demo_grid-status--delivered";
    if (value === "Đang đợi") status = "dhx-demo_grid-status--in-progress";
    if (value === "Hủy") status = "dhx-demo_grid-status--cancelled";
    return `
        <div class='dhx-demo_grid-template'>
            <div class='dhx-demo_grid-status ${status}'></div>
            <span>${value}</span>
        </div>
    `;
}

function windows(id, data = null) {
    // get date now
    let date = dateNow();

    // get form data
    let bill_id = "";
    let table_order_name = "";
    let date_check_in = date[3] + ":" + date[4] + ":" + date[5];
    let date_check_out = "";
    let total = "";
    let status = "";
    let area_name = "";
    let promotion_description = "";
    let note = "";

    if (data) {
        bill_id = data.bill_id.trim();
        table_order_name = data.table_order_name.trim();
        date_check_in = data.date_check_in.trim();
        date_check_out = data.date_check_out.trim();
        total = data.total.trim();
        status = statusChange(data.status.trim());

        area_name = data.area_name.trim();
        promotion_description = data.promotion_description.trim();
        note = data.note.trim();
    }

    if (id == "add_order") {
        getWindowData(function (data) {

            var data = JSON.parse(data);

            // get form structure

            /*  window -------------------------------------------------------------------------------------- */
            dhxWindow = new dhx.Window({ width: 1548, height: 620, closable: true, movable: true, modal: true, title: "Thêm Đơn hàng (order)", });

            /*  add layout -------------------------------------------------------------------------------------- */
            addLayout = new dhx.Layout(null, {
                type: "space",
                cols: [
                    { id: "С1", html: "1", width: 820, header: "Tất cả sản phẩm có sẵn", resizable: true },
                    {
                        type: "wide",
                        rows: [
                            { id: "С2", html: "2", header: "Đơn hàng đang được xử lý", resizable: true },
                            { id: "С3", html: "3", resizable: true },
                        ],
                    }
                ]
            });

            /*  add grid -------------------------------------------------------------------------------------- */
            leftGrid = new dhx.Grid(null, {
                // css: "dhx_demo-grid",
                columns: [
                    { width: 200, id: "food_name", header: [{ text: "Sản phẩm" }], },
                    { width: 100, id: "food_price", header: [{ text: "Đơn giá" }], type: "number", format: "#,#" },
                    { width: 90, id: "food_count", header: [{ text: "Số lượng" }] },
                    { id: "food_note", header: [{ text: "Ghi chú" }] },
                    {
                        width: 100,
                        id: "food_action",
                        gravity: 1.5,
                        header: [{ text: "Actions", align: "center" }],
                        htmlEnable: true,
                        align: "center",
                        template: function () {
                            return "<div class='food-action-buttons'><a class='btn btn-primary btn-add-food detail-add-button'>Thêm</a></div>";
                        },
                    },
                ],
                editable: true,
                autoWidth: true,
                resizable: true,
                eventHandlers: {
                    onclick: {
                        "detail-add-button": function (e, data) {
                            let food_total = data.row.food_count * data.row.food_price;
                            let rowData = {
                                detail_food_name_add: data.row.food_name,
                                detail_price_add: data.row.food_price,
                                detail_count_add: data.row.food_count,
                                detail_total_add: food_total,
                                detail_note_add: data.row.food_note,
                            }

                            // reset leftGrid data
                            data.row.food_count = 1


                            addNewItem(rowData);
                            setAddFormData();
                        },
                    },
                },
                data: data.foodDataSet
            });

            leftGrid.selection.enable();

            // // leftGrid.events.on("afterEditEnd", function (value, row, column) {
            // //     console.log(`left data: ${JSON.stringify(row)}`);
            // //     let food_total = value * row.food_price;

            // //     const rowData = {
            // //         detail_food_name_add: row.food_name,
            // //         detail_price_add: row.food_price,
            // //         detail_count_add: row.food_count,
            // //         detail_total_add: food_total,
            // //         detail_note_add: row.food_note,
            // //     }


            // // });

            rightGrid = new dhx.Grid(null, {
                // css: "dhx_demo-grid",
                columns: [
                    { width: 200, id: "detail_food_name_add", header: [{ text: "Sản phẩm" }], },
                    { width: 100, id: "detail_price_add", header: [{ text: "Đơn giá" }], type: "number", format: "#,#" },
                    { width: 90, id: "detail_count_add", header: [{ text: "Số lượng" }] },
                    { width: 120, id: "detail_total_add", editable: false, header: [{ text: "Tổng" }], type: "number", format: "#,#" },
                    { id: "detail_note_add", header: [{ text: "Ghi chú" }] },
                    {
                        width: 100,
                        id: "detail_action_add",
                        gravity: 1.5,
                        header: [{ text: "Actions", align: "center" }],
                        htmlEnable: true,
                        align: "center",
                        template: function () {
                            return "<span class='action-buttons'><a class='btn btn-danger detail-remove-button'>Xóa</a></span>";
                        },
                    },
                ],
                editable: true,
                autoWidth: true,
                selection: "row",
                resizable: true,
                multiselection: true,
                eventHandlers: {
                    onclick: {
                        "detail-remove-button": function (e, data) {
                            rightGrid.data.remove(data.row.id);
                            setAddFormData();
                        },
                    },
                },
            });

            rightGrid.selection.enable();


            /*  --------------------------------------------------------------------------------------
                    | add form
                    |
                */

            addForm = new dhx.Form(null, {
                css: "dhx_widget--bordered",
                padding: 20,
                rows: [
                    {
                        type: "fieldset", name: "general", label: "Thông tin chung", labelAlignment: "left",
                        rows: [
                            {
                                align: "around", width: "900px", padding: "20px", cols: [
                                    {
                                        rows: [
                                            {
                                                type: "combo", name: "table_order_name", required: true, label: "Bàn", placeholder: "Chọn Bàn", listHeight: "100px", newOptions: true, labelWidth: "270px", padding: "10px",
                                                data: data.tableOptions
                                            },
                                            { type: "input", inputType: "number", name: "area_name", required: true, label: "Khu vực", placeholder: "Khu vực bàn được đặt", labelWidth: "270px", padding: "10px", readOnly: true },
                                        ]
                                    },
                                    {
                                        rows: [
                                            {
                                                type: "combo", name: "promotion_description", label: "Khuyến mãi", placeholder: "Khuyến mãi", listHeight: "100px", labelWidth: "270px", labelWidth: "270px", padding: "10px",
                                                data: data.promotionOptions
                                            },
                                            { type: "input", inputType: "number", name: "count_orders", required: true, label: "Số món (khác nhau)", placeholder: "Số món khác nhau", labelWidth: "270px", padding: "10px", readOnly: true },
                                        ]
                                    },
                                    {
                                        rows: [
                                            { labelWidth: "220px", type: "input", name: "total", required: true, label: "Tạm tính", placeholder: "Tổng tiền", labelWidth: "270px", padding: "10px", readOnly: true },
                                            { type: "input", name: "sum_orders", inputType: "number", required: true, label: "Số lượng món", placeholder: "Tổng số món trong đơn", labelWidth: "270px", padding: "10px", readOnly: true },
                                        ]
                                    }
                                ]
                            },
                        ]
                    },
                    {
                        align: "end",
                        cols: [
                            { type: "button", name: "cancel", view: "link", text: "Xóa" },
                            { type: "button", name: "save_add", view: "flat", text: "Lưu", submit: true, url: "saveOrder" }
                        ],
                    }
                ],
            });

            // addForm.setValue({
            //     "area_name": ["1"]
            // });

            // attaching widgets to Layout cells
            addLayout.getCell("С1").attach(leftGrid);

            addLayout.getCell("С2").attach(rightGrid);
            addLayout.getCell("С3").attach(addForm);

            /*  detail attach to layout -------------------------------------------------------------------------------------- */
            dhxWindow.attach(addLayout);

            dhxWindow.setFullScreen();
            dhxWindow.show();

            addForm.events.on("click", function (name, new_value) {
                if (name == "cancel") {
                    addForm.clear("value");
                } else if (name == "save_add") {
                    var formData = addForm.getValue();
                    var gridData = rightGrid.data.serialize();
                    if (gridData.length == 0) {
                        dhx.alert({ header: "Tạo Đơn hàng", text: "Đơn hàng bạn tạo chưa có món nào", buttonsAlignment: "center", });
                        return
                    } else {

                        if (!formData.table_order_name) {
                            dhx.alert({ header: "Tạo Đơn hàng", text: "Bạn chưa chọn Bàn của đơn hàng", buttonsAlignment: "center", });
                            return
                        } else if (!formData.area_name) {
                            dhx.alert({ header: "Tạo Đơn hàng", text: "Bạn chưa chọn Bàn của đơn hàng", buttonsAlignment: "center", });
                            return
                        } else {

                            $.post("saveOrder", { formData: formData, gridData: gridData })
                                .done(function (data) {
                                    alert("Data Loaded: " + data);
                                }).fail(function () {
                                    console.log("Chưa lấy được dữ liệu để lưu");
                                })
                                .always(function () {
                                    console.log("finished");
                                });
                        }
                    }
                }

            })

            addForm.getItem("table_order_name").events.on("change", function (ids) {
                
                $.post("getAreaId", { table_order_name: ids})
                    .done(function (data) {
                        alert("Data Loaded: " + data);
                    }).fail(function () {
                        dhx.alert({ header: "Tạo Đơn hàng", text: "Chưa lấy được dữ liệu Bàn để tìm Khu vực", buttonsAlignment: "center", });
                    })
                    .always(function () {
                        console.log("finished");
                    });
            });
        });
    } else {
        if (id == "description") {
            const windowHtml =
                "<p>Here is a neat and flexible JavaScript window system with a fast and simple initialization.</p><p>Inspect all the DHTMLX window samples to discover each and every feature.</p><img style='display: block; width: 200px; height: 200px; margin-top: 20px; margin-left: auto; margin-right: auto' src='https://snippet.dhtmlx.com/codebase/data/common/img/01/developer-01.svg'>";
            dhxWindow = new dhx.Window({
                width: 360,
                height: 520,
                closable: true,
                movable: true,
                modal: true,
                title: "Window",
                html: windowHtml,
            });
        } else if (id == "detail_order") {


            $.ajax("./detailLoad?bill_id=" + bill_id).done(function (data) {

                var data = JSON.parse(data);
                // console.log("detail data options: " + JSON.stringify(data));

                /*  Window -------------------------------------------------------------------------------------- */
                dhxWindow = new dhx.Window({ width: 1280, height: 520, closable: true, movable: true, modal: true, title: "Chi tiết đơn hàng", });

                /*  detail toolbar-------------------------------------------------------------------------------------- */
                var toolbarData = [
                    { type: "button", view: "flat", color: "primary", circle: true, icon: "mdi mdi-menu" },
                    { id: "dashboard", value: "Dashboard", icon: "mdi mdi-view-dashboard", group: "page", twoState: true, active: true },
                    { type: "spacer" },
                    { id: "detail-save", type: "button", circle: true, value: "Lưu tất cả", size: "small", icon: "mdi mdi-content-save-all", full: true },
                    { id: "settings2", icon: "mdi mdi-cog", type: "button", view: "link", color: "secondary", circle: true },
                    { type: "text", icon: "mdi mdi-help-circle", value: "Hướng dẫn", tooltip: "Chức năng:: Save tất cả các sản phẩm đã sửa." },
                ];

                // Toolbar initialization
                detailToolbar = new dhx.Toolbar(null, {});
                // loading structure into Toolbar
                detailToolbar.data.parse(toolbarData);

                detailToolbar.events.on("click", function (id, e) {
                    dhx.alert({ header: "Alert Header", text: "Alert text", buttonsAlignment: "center", });
                });

                /*  detail grid-------------------------------------------------------------------------------------- */
                detailGrid = new dhx.Grid(null, {
                    css: "dhx_demo-grid",

                    columns: [
                        // { width: 270, id: "detail_food_name", header: [{ text: "Sản phẩm" }] },
                        {
                            width: 270, id: "detail_food_name", header: [{ text: "Sản phẩm" }], editorType: "combobox", editorConfig: {
                                template: ({ value }) => getOptionsTemplate(value)
                            },
                            options: data.foodOptions,
                            template: (value) => getOptionsTemplate(value),
                            htmlEnable: true
                        },
                        { width: 90, id: "detail_count", header: [{ text: "Số lượng" }], type: "number", format: "#,#" },
                        { width: 100, id: "detail_price", header: [{ text: "Đơn giá" }], type: "number", format: "#,#" },
                        { width: 120, id: "detail_total", editable: false, header: [{ text: "Tổng" }], type: "number", format: "#,#" },
                        { width: 100, id: "detail_bill_id", editable: false, header: [{ text: "Thuộc Bill" }] },
                        {
                            width: 270, id: "detail_size_unit_code", header: [{ text: "Đơn vị: Size" }], editorType: "combobox", editorConfig: {
                                template: ({ value }) => getOptionsTemplate(value)
                            },
                            options: data.sizeUnitOptions,
                            template: (value) => getOptionsTemplate(value),
                            htmlEnable: true
                        },

                        // { width: 100, id: "detail_size_unit_code", editable: true, header: [{ text: "Đơn vị: Size" }] },

                        { id: "detail_note", header: [{ text: "Ghi chú" }] },
                        {
                            width: 120, id: "detail_action", gravity: 1.5, header: [{ text: "Actions", align: "center" }], htmlEnable: true, align: "center",
                            template: function () {
                                return "<span class='action-buttons'><a class='btn btn-warning detail-edit-button'>Lưu</a><a class='btn btn-danger detail-remove-button'>Xóa</a></span>";
                            }
                        }
                    ],
                    editable: true,
                    autoWidth: true,
                    resizable: true,
                    eventHandlers: {
                        onclick: {
                            "remove-button": function (e, data) {
                                dhx.alert({
                                    header: "Alert Header",
                                    text: "Hello",
                                    buttonsAlignment: "center",
                                });
                            },
                            "edit-button": function (e, data) {
                                dhx.alert({
                                    header: "Alert Header",
                                    text: "Hello",
                                    buttonsAlignment: "center",
                                });
                            },
                        },
                    },
                    data: data.detailData
                });

                detailGrid.selection.enable();

                detailGrid.events.on("afterEditEnd", function (value, row, column) {
                    console.log(`column: ${JSON.stringify(column)}`);
                    if ((column.id == "detail_count" && row.detail_count > 0) || (column.id == "detail_price" && row.detail_price > 0)) {
                        row.detail_total = row.detail_count * row.detail_price
                    } else if ((column.id == "detail_food_name") && (row.detail_food_name)) {
                        getAjaxData(function (data) {

                            var data = JSON.parse(data);
                            var foodData = data.foodData;
                            row.detail_count = 1;
                            row.detail_price = foodData.price;
                            row.detail_total = row.detail_count * row.detail_price;
                            row.detail_bill_id = bill_id;

                            // row.detail_size_unit_code = "1:M__Ly size M";

                        }, "getDetailDataToAdd?detail_food_name=" + row.detail_food_name);
                    }

                });

                /*  detail layout -------------------------------------------------------------------------------------- */
                // Layout initialization
                detailLayout = new dhx.Layout(null, {
                    type: "line",
                    cols: [
                        {
                            rows: [
                                { id: "detail-toolbar", height: "content" },
                                { type: "space", rows: [{ id: "detail-grid" }] }
                            ],
                        },
                    ],
                });

                // attaching widgets to Layout cells
                detailLayout.getCell("detail-toolbar").attach(detailToolbar);
                detailLayout.getCell("detail-grid").attach(detailGrid);

                /*  detail attach to layout -------------------------------------------------------------------------------------- */
                dhxWindow.attach(detailLayout);
                dhxWindow.show();

            })
                .fail(function () {
                    alert("Không lấy được dữ liệu từ hệ thống");
                })
                .always(function () {
                    console.log("Done!");
                });

        }


    }

}

function statusChange(status) {
    if (status == "Đã thanh toán") {
        status = "Done";
    } else if (status == "Đã thanh toán") {
        status = "Delivered";
    } else if (status == "Đã giao món") {
        status = "Delivered";
    } else if (status == "Đang đợi") {
        status = "In-progress";
    } else if (status == "Hủy") {
        status = "Cancelled";
    } else {
        status = "Undefined";
    }

    return status;
}

function dateNow() {
    let date = new Date();

    console.log(`date: ${date}`);
    let year = date.getFullYear();
    let month = date.getMonth();
    let day = date.getDay();
    let hour = date.getHours();
    let minute = date.getMinutes();
    let second = date.getSeconds();

    return [year, month, day, hour, minute, second];
}

function addNewItem(rowData) {

    var data = rightGrid.data
    var row = data.add({
        detail_food_name_add: rowData.detail_food_name_add,
        detail_price_add: rowData.detail_price_add,
        detail_count_add: rowData.detail_count_add,
        detail_total_add: rowData.detail_total_add,
        detail_note_add: rowData.detail_note_add
    });

}

function setAddFormData() {
    var rightGridData = rightGrid.data.serialize();

    var sum_orders = 0;
    var count_orders_arr = [];
    var total = 0;
    for (var index = 0; index < rightGridData.length; index++) {
        sum_orders += Number(rightGridData[index].detail_count_add);
        if (count_orders_arr.indexOf(rightGridData[index].detail_food_name_add) == -1) {
            count_orders_arr.push(rightGridData[index].detail_food_name_add);
        }

        total += Number(rightGridData[index].detail_price_add) * Number(rightGridData[index].detail_count_add);
    }

    var count_orders = count_orders_arr.length;

    total = total.toLocaleString();

    addForm.setValue({
        "sum_orders": sum_orders,
        "count_orders": count_orders,
        "total": total
    });

    // return { "sum_orders": sum_orders, "count_orders": count_orders, "total": total }

}

/*
    |
    | running
    |
*/

// layout()
toolbar();
// grid()
orderGrid();

function getWindowData(callBack) {
    $.ajax("getDataToAddForm")
        .done(function (data) {
            callBack(data);
            console.log(data);
        })
        .fail(function () {
            alert("Không lấy được dữ liệu từ hệ thống!!");
        })
        .always(function () {
            console.log("Done!!");
        });
}

function getAjaxData(callBack, url) {
    $.ajax(url)
        .done(function (data) {
            callBack(data);
            // console.log(data);
        })
        .fail(function () {
            alert("Không lấy được dữ liệu từ hệ thống??");
        })
        .always(function () {
            console.log("Done!!");
        });
}

function getAjaxData2(callBack, url) {
    $.ajax({
        method: "POST",
        url: url,
        data: { name: "John", location: "Boston" }
    })
        .done(function (data) {
            callBack(data);
            // console.log(data);
        })
        .fail(function () {
            alert("Không lấy được dữ liệu từ hệ thống??");
        })
        .always(function () {
            console.log("Done!!");
        });

}
