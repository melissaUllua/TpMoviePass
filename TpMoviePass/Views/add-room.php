<?php ///falta modificar
    require_once('nav.php');
    use Models\Cinema as Cinema;
    if((isset($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1))){
?>
<main class="py-5">
     <section id="listado" class="mb-5">
          <div class="container">
          <?php 
               if(isset($message))
               {
                    ?> <p class= "message-small"> <?php echo $message; ?> </p>
                    <?php  
               }                                                                                                                                                                                                                                                                                                                                                                                                                              
          ?>
               <h2 class="mb-4 message">Add rooms </h2>
               <?php
              // var_dump($cinemaID); 
               //for($i=0 ; $i < $totalRooms ; $i++){ ?>
               <form action="<?php echo FRONT_ROOT."Room/Add/"?>" method="post" class="bg-light-alpha p-5">
                    <div class="row">                         
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Name</label>
                                   <input type="text" name="roomName" value="" class="form-control">
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Capacity</label>
                                   <input type="number" name="roomCapacity" value="" class="form-control" min="1">
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Type</label>
                                   <select name="roomIs3d" id="" class="form-control">
                                   <option value="1">3D</option>
                                   <option value="0">2D</option>
                                   </select>
                              </div>
                         </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for="">Ticket Price</label>
                                   <input type="number" name="roomTicketPrice" value="" class="form-control">
                              </div>
                         </div> 
                         <div class="col-lg-4">
                              <div class="form-group">
                                   <label for=""></label>
                                   <input type="hidden" name="cinemaID" value="<?php echo $idCinema; ?>" class="form-control">
                              </div>
                         </div>
                    </div>
                    <button type="submit" name="button" class="btn btn-dark ml-auto d-block">Add</button>
               <?php /*} llave del for each*/?>
               </form>
          </div>
          <?php   } else {  ?> <p class= "message"> You are not authorized to view this section <?php }?>
     </section>
</main>