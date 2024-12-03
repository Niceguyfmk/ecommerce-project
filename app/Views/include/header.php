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
    </head>
    <body>
        <div class="wrapper">

