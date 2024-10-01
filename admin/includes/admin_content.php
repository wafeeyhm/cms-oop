<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Blank Page
                <small>Subheading</small>
            </h1>
            <?php
            
                // $user = new User();
                // $user->username = "chase";
                // $user->password = "pass123";
                // $user->first_name = "James";
                // $user->last_name = "Howlett";

                // $user->save();

                // $user = User::find_users_by_id(7);
                // $user->username = "gabby";
                // $user->password = "123456";
                // $user->save();

                $photos = Photo::find_all();
                foreach ($photos as $photo) {
                    # code...
                    echo $photo->title;
                }


            ?>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i> Blank Page
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

</div>