<div id="carouselBook" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">  
    <div class="carousel-item active">
      <a href="<?php echo URL.'index.php/res/book/index/self';?>">
      <img src="<?php echo URL.'img/banners/books/Slide1.JPG';?>" class="d-block w-100" alt="...">
      </a>
    </div>
    <div class="carousel-item">
      <a href="<?php echo URL.'index.php/res/book/index/self';?>">
      <img src="<?php echo URL.'img/banners/books/Slide2.JPG';?>" class="d-block w-100" alt="...">
      </a>
    </div>
    <div class="carousel-item">
      <a href="<?php echo URL.'index.php/res/book/index/self';?>">
      <img src="<?php echo URL.'img/banners/books/Slide3.JPG';?>" class="d-block w-100" alt="...">
      </a>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<script>
  var myCarousel = document.querySelector('#carouselBook')
  var carousel = new bootstrap.Carousel(myCarousel)
</script>