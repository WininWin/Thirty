<script>
  
  $("#Home").removeClass("active");
  $("#About").removeClass("active");
  $("#Ranking").addClass("active");
     function formatTime(time) {
        var h = m = s = ms = 0;
        var newTime = '';

        h = Math.floor( time / (60 * 60 * 1000) );
        time = time % (60 * 60 * 1000);
        m = Math.floor( time / (60 * 1000) );
        time = time % (60 * 1000);
        s = Math.floor( time / 1000 );
        ms = time % 1000;

        newTime = pad(m, 2) + ':' + pad(s, 2) + ':' + pad(ms, 2);
        return newTime;
      }
      
      function pad(num, size) {
        var s = "0000" + num;
        return s.substr(s.length - size);
      }



</script>

 <div class="row marketing">
        <div class="col-lg-6">
          <h3>Top score</h3>
          <ol><?php
          /*ranking for top score */
          $sql = "SELECT * FROM score ORDER BY max_score DESC LIMIT 30";
          $result = $link->query($sql);
             while ($row = $result->fetch_assoc()) {
              if($row['max_score'] != 0){
              echo '<li><div class="display_rank"><span><img src = http://graph.facebook.com/'.$row["get_id"].'/picture?type=small style="
    height: 50px;
    width: 50px;></span><span id = "show_name">'.$row["name"].'</span><span id ="show_score">'.$row["max_score"].'</span></div></li><br>';
             }
             }
           
           ?></ol>

          <h3>Score at Thirty</h3>
        
          <ol><?php
          /*ranking for score at 30 seconds */
             $sql = "SELECT * FROM score ORDER BY thirty_score DESC LIMIT 30";
              $result = $link->query($sql);
             while ($row = $result->fetch_assoc()) {
              if($row['thirty_score'] != 0){
              echo '<li><div class="display_rank"><span><img src = http://graph.facebook.com/'.$row["get_id"].'/picture?type=small style="
    height: 50px;
    width: 50px;
"></span><span id = "show_name">'.$row["name"].'</span><span id ="show_score">'.$row["thirty_score"].'</span></div></li><br>';
             }
             }
            
           ?></ol>
         </div>

        <div class="col-lg-6">
          <h3>Time at thirty</h3>
          <ol><?php
          /*Ranking for time at thirty points */
              $sql = "SELECT * FROM score WHERE time_score != 0 ORDER BY time_score LIMIT 30";              $result = $link->query($sql);
              $j = 0;
             while ($row = $result->fetch_assoc()) {
              if($row["time_score"] != 0){
             echo '<li><div class="display_rank"><span><img src = http://graph.facebook.com/'.$row["get_id"].'/picture?type=small style="
    height: 50px;
    width: 50px;
"></span><span id = "show_name">'.$row["name"].'</span><span class = "time_score" id ="tem'.$j.'"></span></div></li><br>';
              echo '<script>$("#tem'.$j.'").text(formatTime('.$row["time_score"].'));</script>';
             $j = $j + 1;
             }
             }
           ?></ol>

          <h3>Top survival time</h3>
          
            <ol><?php
            /*ranking for top survival time */
             $sql = "SELECT * FROM score ORDER BY top_time  DESC LIMIT 30";
              $result = $link->query($sql);
              $i = 0;
             while ($row = $result->fetch_assoc()) {
              if($row["max_score"] >= 30){
             
              echo '<li><div class="display_rank"><span><img src = http://graph.facebook.com/'.$row["get_id"].'/picture?type=small style="
    height: 50px;
    width: 50px;
"></span><span id = "show_name">'.$row["name"].'</span><span class = "time_score" id ="temp'.$i.'"></span></div></li><br>';
              echo '<script>$("#temp'.$i.'").text(formatTime('.$row["top_time"].'));</script>';
             $i = $i + 1;
             }
             }
          
           ?></ol>
        </div>
      </div>