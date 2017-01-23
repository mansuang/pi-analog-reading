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

        $stmt = $dbh->query('SELECT * from data ');
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<style>
.container {
  margin: 10px 20px;
}
</style>


</head>

<body>

<div id="app" >
  <div class="container">
    <h1 class="title is-1">Melon Team I/O Status on {{ now }}</h1>
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Current Value</th>
            <th>Control ?</th>
            <th>SET HI</th>
            <th>SET LOW</th>
            <th>TRIG ON</th>
            <th>DELAY (Sec)</th>
            <th>Output</th>
            <th>Updated at</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="d ,idx in data">
            <td>{{ d.name }}</td>
            <td>{{ d.current_value }}</td>
            <td>{{ d.is_control }}</td>
            <td>{{ d.set_hi }}</td>
            <td>{{ d.set_low }}</td>
            <td>{{ d.trig_on_state }}</td>
            <td>{{ d.delay_sec }}</td>
            <td>{{ d.output }}</td>
            <td>{{ d.updated_at }}</td>
          </tr>
        </tbody>
      </table>
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
        data:{},
        now: new Date()
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
                self.now = new Date();
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
