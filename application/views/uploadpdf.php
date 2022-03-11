<html>

<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
    <div style="margin-top: 50px;" class="container">

        <form action="/Testing/uploadPdf" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="exampleFormControlFile1">Upload Pdf File</label>
                <input class="form-control-file" type="file" name="file" size="50" />
                <br />
                <input class="btn btn-active" accept=".pdf" type="submit" value="Upload" />

            </div>
        </form>


        <form action="/Testing/searchByKeyword" method="post" class="form-inline">
            <div class="form-group mx-sm-3 mb-2">
                <label for="inputPassword2" class="sr-only">Keyword</label>
                <input type="text" class="form-control" name="keyword" id="keyword" placeholder="keyword">
            </div>
            <button type="submit" class="btn btn-primary mb-2">Search</button>
        </form>

        <div>
            <ul>
                <?php
                foreach ($files as $file) {
                ?>
                    <li><a href="/<?= $file->url ?>"><?= $file->name ?></a></li>

                <?php
                }

                ?>
            </ul>
        </div>


    </div>
</body>

</html>