<?php
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/car.php";

  $app = new Silex\Application();
  $app['debug'] = true;
  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  $app->get("/", function() use ($app) {

    return $app['twig']->render('dealership.html.twig');

  });

  $app->get("/search_results", function() use ($app) {

    $porsche = new Car("2014 Porsche 911", 114991, 7864, "/img/porsche.jpg");
    $ford = new Car("2011 Ford F450", 55995, 14241, "/img/ford.jpg");
    $lexus = new Car("2013 Lexus RX 350", 44700, 20000, "/img/lexus.jpg");
    $mercedes = new Car("Mercedes Benz CLS550", 39900, 37979, "/img/cls550.jpg");
    $mercedes->setPrice(35000.925);
    $porsche->setPrice("hotdog");
    $user_price = $_GET["user_price"];
    $user_miles = $_GET["user_miles"];

    $cars = array($porsche, $ford, $lexus, $mercedes);
    $arrayOfCars = searchCars($cars, $user_price, $user_miles);
    $whatToReturn = "";
    if (empty($arrayOfCars)) {
      $whatToReturn = '<p>No cars match your search</p>';
    }
    else {
      foreach ($arrayOfCars as $specific_car) {
        $car_price = $specific_car->getPrice();
        $car_make = $specific_car->getMake();
        $car_miles = $specific_car->getMiles();
        $car_picture = $specific_car->getPicture();
        $whatToReturn .= "<div><img src='$car_picture'</div>
              <p>$car_make</p>
              <p>$car_miles miles</p>
              <p>$$car_price</p>
            ";
      }
    }

    // creates the array with one index: the key value of the String $whatToReturn 
    return $app['twig']->render('search_results.html.twig', array('whatToReturn' => $whatToReturn));

  });

    return $app;
?>
