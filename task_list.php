<!--
* Author: Beulah Nwokotubo
* File Name: task_list.php
* Date Created: 23 November 2023
* Description: This file contains code that implements task search and filter functionalities.
 -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="# ">
    <link rel="stylesheet" href="css\style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Search and Filter</title>
    <script type="text/javascript">
        function searchTask() {
           var searchValue = document.getElementById('taskSearch').value;
            document.onload();
         window.location.href = 'index.php?search=' + encodeURIComponent(searchValue);
        }
    </script>
</head>

<script>
    $(document).ready(function() {
        searchTasks();
        $('#searchButton').click(function() {
            searchTasks();
        });
    });

    function searchTasks() {
        var searchValue = $('#taskSearch').val(); // obtain search value

        $.ajax({
            url: 'fetch_tasks.php?search=' + encodeURIComponent(searchValue),   
            type: 'GET',
            success: function(data) {
                $('#taskTable tbody').html(data); // update form content
            }
        });
    }
</script>

<footer>
    <p>@copyright 2023 Beulah Nwokotubo</p>
</footer>

</body>

