<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title class><?= $pageTitle ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
            <!-- Font-awesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
          crossorigin="anonymous" 
          referrerpolicy="no-referrer"/>
        <!--Stylesheet-->
        <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
        <style>
            footer {
                position: flex; 
                bottom: 0;
                width: 100%;
                height: 50px; /* adjust the height value as needed */
                text-align: center; 
            }
        </style>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                setupCloning({
                    containerSelector: "#attributes-container",
                    addButtonSelector: "#add-attribute",
                    removeButtonSelector: "#remove-attribute",
                    rowClass: "attribute-row",
                    inputPattern: /\[\d+\]/, // Matches [0], [1], etc.
                });
            });
        </script>
            <!-- chart js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

    </head>
    <body>
        <div class="wrapper">

