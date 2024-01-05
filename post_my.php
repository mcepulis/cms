

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Start Bootstrap</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">About</a>
                    </li>
                    <li>
                        <a href="#">Services</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Post Content Column -->
            <div class="col-lg-8">

                <!-- Blog Post -->

                <!-- Title -->
               
                <?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>


<body>

    <!-- Navigation -->
    <?php include 'includes/navigation.php'; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">
            <?php
            if(isset($_GET['p_id'])) {
               $the_post_id = $_GET['p_id'];
            $view_query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = $the_post_id ";
            $send_query = mysqli_query($connection, $view_query);

            if(!$send_query) {
                die("query failed" );
            }

            if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
                $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
            } else {
                $query = "SELECT * FROM posts WHERE post_id = $the_post_id AND post_status = 'published' ";
            }
            $select_all_posts_query = mysqli_query($connection,$query);
            if(mysqli_num_rows($select_all_posts_query) < 1) {
                echo "<h1 class='text-center'>No posts available</h1>";
            } else {
            $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
            $queryPost_connection = mysqli_query($connection, $query);
            while($row = mysqli_fetch_assoc($queryPost_connection)) {
                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = $row['post_content'];
            ?>
<h1 class="page-header">
                    Posts
                </h1>

                <!-- First Blog Post -->
                <h2>
                    <a href="#"><?php echo $post_title ?></a>
                </h2>
                <p class="lead">
                    by <a href="/cms/index.php"><?php echo $post_author ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span><?php echo $post_date ?></p>
                <hr>
                <img class="img-responsive" src="/cms/images/<?php echo imagePlaceholder($post_image); ?>" alt="">
                <hr>
                <p><?php echo $post_content ?></p>
            

                <hr>

                <?php } 
                
            
        
                

           
            
        
            ?>

   <!-- Blog Comments -->
<?php
if(isset($_POST['create_comment'])) {

    $the_post_id = $_GET['p_id'];
    $comment_author = $_POST['comment_author'];
    $comment_email = $_POST['comment_email'];
    $comment_content = $_POST['comment_content'];

    if(!empty($comment_author) && ($comment_email) && ($comment_content) ) {

        $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) ";

        $query .= "VALUES ($the_post_id, '{$comment_author}', '{$comment_email}', '{$comment_content}', 'unaproved', now())";
    
    $create_comment_query = mysqli_query($connection,$query);
        if(!$create_comment_query) {
            die('QUERY FAILED' . mysqli_error($connection));
        }


        

    // $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 ";
    // $query .= "WHERE post_id = $the_post_id ";
    // $update_comment_count = mysqli_query($connection,$query);


    } else {

        echo "<script>alert('Fields cannot be empty')</script>";

    }
}
   
    // redirect("location: /cms/post.php?p_id = $the_post_id");
    }

     


 

?>
                <!-- Comments Form -->
                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form action="" method="post" role="form">
                    <div class="form-group">
                        <label for="author">Author:</label>
                           <input type="text" class="form-control" name="comment_author">
                        </div>
                        <div class="form-group">
                        <label for="email">Email:</label>
                            <input type="email" class="form-control" name="comment_email">
                        </div>
                        <div class="form-group">
                        <label for="comment">Your Comment:</label>
                            <textarea name="comment_content" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->

<?php

$query = "SELECT * FROM comments WHERE comment_post_id = {$the_post_id} ";
$query .= "AND comment_status = 'approve' ";
$query .= "ORDER BY comment_id DESC ";
$select_comment_query = mysqli_query($connection, $query);
if(!$select_comment_query) {
    die('Query Failed' . mysqli_error($connection));
}
while ($row = mysqli_fetch_array($select_comment_query)) {
    $comment_date = $row['comment_date'];
    $comment_content = $row['comment_content'];
    $comment_author = $row['comment_author'];


?>

                <!-- Comment -->
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>
                    <div class="media-body">
                        <h4 class="media-heading"><?php echo $comment_author;?>
                            <small><?php echo $comment_date;?></small>
                        </h4>
                        <?php echo $comment_content;?>
                    </div>
                </div>
    <?php }   } else {

header("Location: index.php");

} ?>
                
            </div>

            <!-- Blog Sidebar Widgets Column -->
            
            <?php include 'includes/sidebar.php'; ?>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->

       <?php include 'includes/footer.php'; ?>


       <script>


       </script>

</body>

</html>

             
                </div>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4">

                <!-- Blog Search Well -->
                <div class="well">
                    <h4>Blog Search</h4>
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <span class="glyphicon glyphicon-search"></span>
                        </button>
                        </span>
                    </div>
                    <!-- /.input-group -->
                </div>

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Blog Categories</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <ul class="list-unstyled">
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                                <li><a href="#">Category Name</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>

                <!-- Side Widget Well -->
                <div class="well">
                    <h4>Side Widget Well</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore, perspiciatis adipisci accusamus laudantium odit aliquam repellat tempore quos aspernatur vero.</p>
                </div>

            </div>

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2014</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
