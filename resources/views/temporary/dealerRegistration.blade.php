<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="container">
    <form>
        <div class="form-group row">
            <label for="name" class="col-4 col-form-label">Name</label>
            <div class="col-8">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-user-circle"></i>
                    </div>
                    <input id="name" name="name" type="text" required="required" class="form-control here">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="number" class="col-4 col-form-label">Phone Number</label>
            <div class="col-8">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-address-card"></i>
                    </div>
                    <input id="number" name="number" type="text" required="required" class="form-control here">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-4 col-form-label">Email</label>
            <div class="col-8">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-address-card"></i>
                    </div>
                    <input id="email" name="email" type="text" class="form-control here">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="age" class="col-4 col-form-label">Age</label>
            <div class="col-8">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-address-card"></i>
                    </div>
                    <input id="age" name="age" type="text" class="form-control here" required="required">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="qualification" class="col-4 col-form-label">Qualification/Experience</label>
            <div class="col-8">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-address-card"></i>
                    </div>
                    <input id="qualification" name="qualification" type="text" class="form-control here">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="address" class="col-4 col-form-label">Address</label>
            <div class="col-8">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-file-image-o"></i>
                    </div>
                    <input id="address" name="address" type="text" class="form-control here" required="required">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="photo" class="col-4 col-form-label">Photo</label>
            <div class="col-8">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-file-image-o"></i>
                    </div>
                    <input id="photo" name="photo" type="text" class="form-control here">
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="offset-4 col-8">
                <button name="submit" type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
</div>

</body>
</html>