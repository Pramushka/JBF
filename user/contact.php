<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="with=device-width,initial-scale=1.0">
        <title>Web site</title>
        <link rel="stylesheet" href="../assets/css/contact.css">
        <link rel="preconnect" href="https://fonts.gtatic.com">
        <link rel="https://fonts.googleapis.com/css?
        family=Poppins:wght@100;200;300;400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
        <body>



        <?php include 'navbar.php'; ?>



<!-----------------contact---------------------->

            <section class="location">

            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63371.80392194407!2d79.81500584707467!3d6.9219220848496485!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae253d10f7a7003%3A0x320b2e4d32d3838d!2sColombo!5e0!3m2!1sen!2slk!4v1720186791660!5m2!1sen!2slk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
           
            </section>

            <section class="contact-us">

                <div class="row">
                    <div class="contact-col">
                        
                        <div>
                            <i class="fa fa-phone" ></i>
                            <span>
                            <h9>+94112749551</h9>
                            <p>Contact agent from here!.</p>
                            </span>
                        </div>
                        <div>
                            <i class="fa fa-envelope" ></i>
                            <span>
                            <h9>jobforceq@gmail.com</h9>
                            <p>Email your query.</p>
                            </span>
                        </div>
                    </div>
 
                </div>

            </section>


        
        <?php include 'footer.php'; ?>


<!---------------------java script for toggle menu---------------------------->
<script>
 var navlinks=document.getElementById("navlinks")

 function showmenu(){
    navlinks.style.right="0";
 }
 function hidemenu(){
    navlinks.style.right="-200px";
 }
</script>
        </body>
   
</html>