<?php
if( isset($_GET['d']) && $_GET['d']='1')
{
    $config = require('config.php');
    try {
        $user = $config['mysql']['username'];
        $pass= $config['mysql']['password'];
        $dbh = new PDO('mysql:host=' .$config['mysql']['host']. ';dbname=' . $config['mysql']['database'], $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $stmt = $dbh->query('SELECT * from temp WHERE id=1');
        $data = $stmt->fetchObject();
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}
?>
<html>

<head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.2.3/css/bulma.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
</head>

<body>

<div id="app" >

  <div class="notification">
    Data Time: <strong>{{ data.created_at }}</strong>
  </div>


  <div class="tile is-vertical is-8">
    <div class="tile">
      <div class="tile is-parent">
        <article class="tile is-child notification is-primary">
          <p class="title">Reading</p>
          <p class="subtitle" id="reading">{{ data.reading }}</p>
        </article>
        <article class="tile is-child notification is-warning">
          <p class="title">Voltage</p>
          <p class="subtitle" id="voltage">{{ data.voltage }}</p>
        </article>
        <article class="tile is-child notification is-info">
          <p class="title">Temp</p>
          <p class="subtitle" id="temp">{{ data.temp }}</p>

        </article>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child notification is-danger" v-show="relayOn">
          <p class="title">Relay</p>
          <p class="subtitle" id="temp">ON</p>

        </article>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.0.3/vue.js"></script>
<script>

$(function(){
    var timer = setInterval(function(){ app.getData() },1000);
});

var app = new Vue({
    el: '#app',

    data: {
        data:{
            reading: -1,
            voltage: -1,
            temp: -1
        }
    },

    methods: {
        getData: function() {
            var self = this;
            $.ajax({
              method: "GET",
              url: "<?php echo $_SERVER['PHP_SELF']; ?>",
              data: { d: "1"}
            })
              .done(function( msg ) {
                self.data = msg;
              });
        }
    },

    computed: {
        relayOn: function(){
            return this.data.voltage > 2 ? 1 : 0;
        }
    },

    mounted: function(){
        var self = this;
        //self.getData();

    }
});

</script>

</body>
</html>
