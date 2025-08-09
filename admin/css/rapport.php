<?php 

if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
    header("HTTP/1.1 403 Forbidden");
    header("location:../error.php");
    exit();
}

// Sinon, afficher le CSS normalement
header("Content-Type: text/css");
?>



.navbar_rapport {
    /* padding: 15px; */
    display:flex; 
    padding-left: 10px !important;
    justify-content: space-around; 
    align-items:center;
    background: var(--gradient);
    border-bottom: var(--text-color);
    flex-direction: row;
    margin-bottom: 20px;
    border-radius: 10px;
}

.add-btn {
    background: var(--addUser) !important;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: background-color 0.3s;
}



.add-btn i {
    font-style: normal;
    font-weight: bold;
}

#search {
    padding: 8px 15px;
    border: 1px solid var(--text-color);
    border-radius: 4px;
    font-size: 14px;
    width: 200px;
    transition: width 0.3s, border-color 0.3s;
}

#search:focus {
    outline: none;
    border-color: #4CAF50;
    width: 250px;
}

/* Responsive pour mobile */
@media (max-width: 768px) {
    .navbar_rapport {
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
        padding: 10px;
    }
    
    .add-btn {
        justify-content: center;
        padding: 10px;
    }
    
    #search {
        width: 100%;
        box-sizing: border-box;
    }
    
    #search:focus {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .navbar_rapport {
        padding: 8px;
    }
    
    .add-btn, #search {
        font-size: 13px;
        padding: 8px 12px;
    }
}


/* end design search */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        /* background: white; */
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table-header {
        background: var(--gradient); 
        padding: 20px;
        text-align: center;
    }

    h2 {
        color: var(--titleColor);
        font-size: 24px;
        margin-bottom: 5px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    thead {
        background: var(--text-light);
        color:var(--secondary-color);
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid var(--text-color);
    }

    th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 1px;
    }
    tbody td{
        color: var(--text-color) !important
    }
    tbody tr {
        background-color: var(--secondary-color);
        transition: background-color 0.3s ease;
    }
    

    tbody tr:hover {
        /* color: var(--secondary-color); */
        background-color: var(--text-light);
    }

  

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        margin: 0 3px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-view {
        background: var(--modifyBtn);
        color: white;
    }

    .btn-view:hover {
        background: var(--modifyHover);
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
    }

    .btn-delete {
        background: var(--deleteBtn);
        color: white;
    }

    .btn-delete:hover {
        background: var(--deleteHover);
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(231, 76, 60, 0.3);
    }

    .actions {
        white-space: nowrap;
    }

    .document-id {
        font-weight: 600;
        color: #2c3e50;
    }

    .employee-id {
        color: #7f8c8d;
        font-style: italic;
    }

    .add-btn {
        background: linear-gradient(135deg, #27ae60, #2ecc71);
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(39, 174, 96, 0.3);
    }

    .add-btn:hover {
        background: linear-gradient(135deg, #229954, #27ae60);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        backdrop-filter: blur(10px);
        height: 100%;
        /* background: rgba(0, 0, 0, 0.7); */
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: var(--secondary-color);
        padding: 30px;
        border-radius: 15px;
        width: 90%;
        max-width: 500px;
        box-shadow: var(--shadowForm);
        transform: scale(0.7);
        opacity: 0;
        transition: all 0.3s ease;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-overlay.active .modal-content {
        transform: scale(1);
        opacity: 1;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--text-color);
    }

    .modal-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--titleColor);
        margin: 0;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: var(--text-light);
        transition: color 0.3s ease;
        padding: 0;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .close-btn:hover {
        color: var(--text-color);
        /* background: #fdeaea; */
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text-color);
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background:var(--text-light);
    }

    .form-select:focus {
        outline: none;
        border-color: #3498db;
        background: var(--text-light);
        box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }

    .file-input {
        position: absolute;
        left: -9999px;
    }

    .file-input-label {
        display: block;
        padding: 12px 15px;
        border: 2px dashed #bdc3c7;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: var(--secondary-color);
        color: #7f8c8d;
    }

    select {
        background: var(--text-light) !important;
    }
    .file-input-label:hover {
        /* border-color: var(--text-light); */
        background: var(--text-light);
        color: var(--text-color);
    }

    .file-input-label.has-file {
        border-color: #27ae60;
        background: #f1f8e9;
        color: #27ae60;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 25px;
        justify-content: flex-end;
    }

    .btn-submit {
        background: var(--gradient);
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }


    .select2-search--dropdown{
        background-color: var(--input-light);
        color: var(--text-color)
    }

    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable{
        background-color: var(--input-light);
        color: var(--text-color)
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered{
        background-color:var(--input-light);
    }


    .select2-container--default .select2-results__option--highlighted.select2-results__option--selectable{
         background-color: #229954;
    }


    .btn-submit:hover {
        background: var(--gradientRotate);
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(52, 152, 219, 0.3);
    }

    .btn-cancel {
        background: #95a5a6;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #7f8c8d;
        transform: translateY(-1px);
    }

    .alert {
        padding: 15px;
        margin: 20px;
        border-radius: 8px;
        font-weight: 500;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container {
            margin: 0;
            border-radius: 0;
        }

        .table-wrapper {
            padding: 0;
        }

        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }



        tbody tr {
            border: 2px solid #e3e3e3;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 12px;
            background: var(--secondary-color);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            position: relative;
        }

        td {
            border: none;
            position: relative;
            padding: 12px 12px 12px 40%;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
            min-height: 50px;
            display: flex;
            align-items: center;
        }

        td:before {
            content: attr(data-label);
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 35%;
            padding-right: 10px;
            white-space: nowrap;
            font-weight: 700;
            color:  var(--text-color);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .actions {
            padding-left: 4px !important;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .modal-content {
            width: 95%;
            padding: 20px;
            margin: 10px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit, .btn-cancel {
            width: 100%;
            margin: 0;
        }
    }

