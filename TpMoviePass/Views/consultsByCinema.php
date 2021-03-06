<?php
     require_once('nav.php');

    use Models\Cinema as Cinema; 

?>
<main class="py-5">
<?php  if(isset($message))
               {
                    ?> <p class= "message-small"> <?php echo $message; ?> </p>
                    <?php   
               }   
               ?>
    
    
        <section id="listado" class="mb-5">
            <div class="container">
           
                <h2 class="mb-4 message">Select Cinema</h2>
               <div class= "container-fluid bg-light-alpha pt-3 pb-3 pl-5 pr-5">
                <form action="<?php echo FRONT_ROOT."Purchase/ConsultByCinema"?>" method="get" >
                    <div class="row">
                             <div class="form-group">
                             <select name="IdMovie" id=""class="form-control">
                             <?php
                                 foreach($cinemaList as $Cinema){
                                  
                                ?>
                             
                                <option class= "control-form" value="<?php echo $Cinema->getCinemaId(); ?>"><?php echo $Cinema->getCinemaName();?></option>
                                 <?php } ?>
                              
                                </select>
                                <div class="form-group">
                                   <label for=""><strong>Since</strong></label>
                                   <input type="date" name="firstDate" value="" class="control-form" required>
                              </div>
                              <div class="form-group">
                                   <label for=""><strong>To</strong></label>
                                   <input type="date" name="lastDate" value="" class="control-form" required>
                              </div>
                             </div>
                             <button type="submit" class="btn btn-dark ml-auto">Select</button>
                         </div>                
                         </form>
                         
                </div>
                                 </div>
        </section>
  
</main>