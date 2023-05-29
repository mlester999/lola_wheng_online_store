<?php 
    global $page_name;
    $page_access_no = 2;
    
    require_once('../../config.php'); 
    require_once('../../includes/sess_auth.php');

    validate_page_access($_settings->userdata('login_type'), $page_access_no);
?>

<?php 
  $con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

  function query($query)
  {
    global $con;

    $result = mysqli_query($con, $query);
    if($result)
    {
      if(!is_bool($result) && mysqli_num_rows($result) > 0)
      {
        $res = [];
        while ($row = mysqli_fetch_assoc($result)) {
          $res[] = $row;
        }

        return $res;
      }
    }

    return false;
  }
?>

<!DOCTYPE html>
<html lang="en">
    <?php require_once('includes/header.php') ?>

    <body class="hold-transition sidebar-mini layout-navbar-fixed">
        <div class="wrapper">
            <!-- Navbar -->
            <?php require_once('includes/navbar.php') ?>

            <!-- Main Sidebar Container -->
            <?php require_once('includes/sidebar.php') ?>

            <?php $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';  ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper"> 
                <div class="container-fluid">
                    <?php 
                        if(!is_dir($page) && $page == 'myprofile') {
                            require_once 'accounts/' . $page .'.php';
                        }else
                        if(!file_exists($page.".php") && !is_dir($page)){
                            require_once '404.php';
                        }else{
                            if(is_dir($page))
                                require_once $page.'/index.php';
                            else
                                require_once $page .'.php';
                        }
                    ?>

                    <?php
                        // echo "<pre>";
                        // // print_r($_SERVER);
                        // print_r($_SESSION);
                        // echo "</pre>";
                    ?>
                </div>
            </div>

            <!-- Control Sidebar -->
            <!-- <aside class="control-sidebar control-sidebar-dark"> -->
                <!-- Control sidebar content goes here -->
            <!-- </aside> -->
  

            <!-- Main Footer -->
            <footer class="main-footer">
                <strong>
                    Copyright &copy; <?php echo date('Y'); ?> 
                    
                    <a href="<?php echo base_url; ?>"><?php echo $_settings->info('name') != false ? $_settings->info('name') : '';?></a>. 
                </strong>
                All rights reserved.
                <div class="float-right d-none d-sm-inline-none">
                    <b>Version</b> 3.2.0
                </div>
            </footer>
        </div>


        <div class="modal fade" id="uni_modal" role='dialog'>
            <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary bg-gradient-teal border-0 rounded-0" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="uni_modal_right" role='dialog'>
            <div class="modal-dialog modal-full-height  modal-md rounded-0" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="fa fa-arrow-right"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirm_modal" role='dialog'>
            <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmation</h5>
                    </div>
                    <div class="modal-body">
                        <div id="delete_content"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary  bg-gradient-primary border-0 rounded-0" id='confirm' onclick="">Continue</button>
                        <button type="button" class="btn btn-secondary rounded-0" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="viewer_modal" role='dialog'>
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
                    <img src="" alt="">
                </div>
            </div>
        </div>

        <!-- REQUIRED SCRIPTS -->
        <?php require_once('includes/footer.php') ?>


        <script>
            // $(document).ready(function(){
                var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'dashboard' ?>';
                var page2 = '<?php 
                                $url1 = $_SERVER['QUERY_STRING']; 
                                $this_url = str_replace('page=',"",substr($url1,0,strpos($url1,'/')));
                                echo $this_url;
                            ?>';
                page = page.replace(/\//g,'_');
                // console.log(page, $('.nav-link.nav-'+page)[0])
                if($('.nav-link.nav-'+page).length > 0){
                    $('.nav-link.nav-'+page).addClass('active');
                    if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
                        $('.nav-link.nav-'+page).addClass('active')
                        $('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
                    }

                    if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
                        $('.nav-link.nav-'+page).parent().addClass('menu-open');
                    }
                }

                if($('.nav-link.nav-'+page2).length > 0){
                    $('.nav-link.nav-'+page2).addClass('active');
                    if($('.nav-link.nav-'+page2).hasClass('tree-item') == true){
                        $('.nav-link.nav-'+page2).addClass('active')
                        $('.nav-link.nav-'+page2).closest('.nav-treeview').parent().addClass('menu-open')
                    }

                    if($('.nav-link.nav-'+page2).hasClass('nav-is-tree') == true){
                        $('.nav-link.nav-'+page2).parent().addClass('menu-open');
                    }
                }
            // });
        </script>

        <?php if($_settings->chk_flashdata('success')): ?>
            <script>
                alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
            </script>
        <?php endif;?>

        <script>
            $(document).ready(function(){
                window.viewer_modal = function($src = ''){
                    start_loader()
                    var t = $src.split('.')
                    t = t[1]
                    if(t =='mp4'){
                        var view = $("<video src='"+$src+"' controls autoplay></video>")
                    }else{
                        var view = $("<img src='"+$src+"' />")
                    }
                    $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
                    $('#viewer_modal .modal-content').append(view)
                    $('#viewer_modal').modal({
                            show:true,
                            backdrop:'static',
                            keyboard:false,
                            focus:true
                            })
                            end_loader()  

                }
                window.uni_modal = function($title = '' , $url='',$size=""){
                    start_loader()
                    $.ajax({
                        url:$url,
                        error:err=>{
                            // console.log()
                            alert("An error occured")
                        },
                        success:function(resp){
                            if(resp){
                                $('#uni_modal .modal-title').html($title)
                                $('#uni_modal .modal-body').html(resp)
                                if($size != ''){
                                    $('#uni_modal .modal-dialog').addClass($size+'  modal-dialog-centered')
                                }else{
                                    $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md modal-dialog-centered")
                                }
                                $('#uni_modal').modal({
                                show:true,
                                backdrop:'static',
                                keyboard:false,
                                focus:true
                                })
                                end_loader()
                            }
                        }
                    })
                }
                window._conf = function($msg='',$func='',$params = []){
                    $('#confirm_modal #confirm').attr('onclick',$func+"("+$params.join(',')+")")
                    $('#confirm_modal .modal-body').html($msg)
                    $('#confirm_modal').modal('show')
                }
            })
            </script>
    </body>
</html>
