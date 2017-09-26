<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Enter survey</title>
    <link rel="stylesheet" href="https://bootswatch.com/flatly/bootstrap.min.css">
    <link rel="stylesheet" href="http://parsleyjs.org/src/parsley.css">
    <script
            src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>
    <script src="<?= base_url() ?>/assets/parsley.min.js"></script>
    <style>
        p.parsley-success {
            color: #468847;
            background-color: #DFF0D8;
            border: 1px solid #D6E9C6;
        }

        p.parsley-error, li.parsley-error, label.parsley-error {
            color: #B94A48;
            background-color: #F2DEDE;
            border: 1px solid #EED3D7;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="container">
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <!--<img src="<?/*= base_url() */?>assets/enus_22.png" data-toggle="tooltip" title="English" alt="English"> |-->
        <img src="<?= base_url() ?>assets/dk_22.png" data-toggle="tooltip" title="Change language to Danish" alt="Danish">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Questionair</div>
                <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#"></a></div>
            </div>

            <div style="padding-top:30px" class="panel-body">

                <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>

                <form method="POST" action="<?= base_url() ?>cardsort" id="loginform" class="form-horizontal" role="form">

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="login-username" type="text" class="form-control" name="username" value=""
                               placeholder="Your first name" required="" data-parsley-errors-container="#container1">
                    </div>
                    <div id="container1"></div>

                    <br>

                    <strong>What is your gender?</strong>
                    <div class="radio">
                        <label><input type="radio" name="gender" value="F" required="" data-parsley-errors-container=".container2">Female</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="gender" value="M" required="" data-parsley-errors-container=".container2">Male</label>
                    </div>
                    <div class="container2"></div>
                    <br>

                    <strong>How old are you?</strong>
                    <div class="radio">
                        <label><input type="radio" name="age" value="Under 18" required="">Under 18</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="age" value="18 to 28" required="">18 to 28</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="age" value="29 to 64" required="">29 to 64</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="age" value="65+" required="">65+</label>
                    </div>

                    <div style="margin-top:10px" class="form-group">
                        <div class="col-sm-12 controls">
                            <button type="submit" class="btn btn-success pull-right">Start</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="signupbox" style="display:none; margin-top:50px"
         class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Sign Up</div>
                <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#"
                                                                                           onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign
                        In</a></div>
            </div>
            <div class="panel-body">
                <form id="signupform" class="form-horizontal" role="form">

                    <div id="signupalert" style="display:none" class="alert alert-danger">
                        <p>Error:</p>
                        <span></span>
                    </div>


                    <div class="form-group">
                        <label for="email" class="col-md-3 control-label">Email</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="email" placeholder="Email Address">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="firstname" class="col-md-3 control-label">First Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="firstname" placeholder="First Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="col-md-3 control-label">Last Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="lastname" placeholder="Last Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-md-3 control-label">Password</label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="passwd" placeholder="Password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="icode" class="col-md-3 control-label">Invitation Code</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="icode" placeholder="">
                        </div>
                    </div>

                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-md-offset-3 col-md-9">
                            <button id="btn-signup" type="button" class="btn btn-info"><i class="icon-hand-right"></i>
                                &nbsp Sign Up
                            </button>
                            <span style="margin-left:8px;">or</span>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #999; padding-top:20px" class="form-group">

                        <div class="col-md-offset-3 col-md-9">
                            <button id="btn-fbsignup" type="button" class="btn btn-primary"><i
                                        class="icon-facebook"></i>   Sign Up with Facebook
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(function () {
        $('#loginform').parsley().on('field:validated', function () {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        })
            .on('form:submit', function () {
                console.log('odosielam form');
            });
    });
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
</body>
</html>