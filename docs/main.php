
	<!-- game window size = 700 * 500 -->
      <div class="jumbotron">
        <div class ="start">
          <p id = "first">Control : W, A, S, D</p>
          <p>Speed Up : L</p>
          <p>Speed Down : K</p>
          <p>Pause : P</p>
          <button type="button" class="btn btn-success" id = "start_button">start</button>

        </div>
        

        <div id="draw-animation">
           
        <div class="two-container" style ="height:500px;"></div>

        <div class = "information">
          <span id ="speed"></span>
          <span id = "time"></span>
          <span id="score" ></span>
        </div>
        
      <script>



      /*Global */
      
      $('#draw-animation').hide();
      $('#start_button').on("click", function(){

        $('.start').remove();
        $('#draw-animation').show();
        pause = !pause;

      });

      /*Global */
       
      var pause = 1;
      var currentlyPressedKeys = {};
      var smove_updown = 0;
      var smove_rightleft =0;
      var enemycircles = [];
      var score = 0;
      var scorecircles = [];
      var init_speed = 2.0;
      var show_speed = 0;
      var thirty_sec_score = 0;
      var thirty_at_time = 0;
      var x = new clsStopwatch();
      var $time;
      var clocktimer;

      var flag = {

        get_score : false,
        is_over : false,
        get_thirty : false,
        at_thirty : false
      
      };

      /*Time Function */

      function pad(num, size) {
        var s = "0000" + num;
        return s.substr(s.length - size);
      }

      function formatTime(time) {
        var h = m = s = ms = 0;
        var newTime = '';

        h = Math.floor( time / (60 * 60 * 1000) );
        time = time % (60 * 60 * 1000);
        m = Math.floor( time / (60 * 1000) );
        time = time % (60 * 1000);
        s = Math.floor( time / 1000 );
        ms = time % 1000;

        newTime = pad(m, 2) + ':' + pad(s, 2) + ':' + pad(ms, 3);
        return newTime;
      }

      function time_show() {
        $time = document.getElementById('time');
        time_update();
      }

      function time_update() {
        $time.innerHTML = formatTime(x.time());
      }

      function time_start() {
        clocktimer = setInterval("time_update()", 1);
        x.start();
      }

      function time_stop() {
        x.stop();
        clearInterval(clocktimer);
      }

      function time_reset() {
        stop();
        x.reset();
        time_update();
      }

      time_show();



      /*
        Get Random position

      */
      function getrandomX(){

          var randomx = Math.random() * 700;
          
          return randomx;

        }

      function getrandomY(){
        var randomy = Math.random() * 500;

        return randomy;
      }

   

      function is_negative(x){
        if( x < 0 ){
          return true;
        }

        return false;

      }

      /*
        Control function
      */

      function handleKeyDown(event) {
        currentlyPressedKeys[event.keyCode] = true;

        if(String.fromCharCode(event.keyCode) == 'P') {
          pause = !pause;
          if(!pause){
            time_start();
          }
          else{
            time_stop();
          }
        }
        if(String.fromCharCode(event.keyCode) == 'K'){
          init_speed = init_speed - 0.5;
          if(init_speed < 2){
            init_speed = 2;
          }
          for(var i in enemycircles){


            if(is_negative(enemycircles[i].velocity.x)){
              enemycircles[i].velocity.x = -init_speed;
            }
            else{
              enemycircles[i].velocity.x = init_speed;
            }
            if(is_negative(enemycircles[i].velocity.y)){
              enemycircles[i].velocity.y = -init_speed;
            }
            else{
              enemycircles[i].velocity.y = init_speed;
            }
          }
          show_speed = init_speed*2 - 4;
          $('#speed').text("Speed: " + show_speed.toString());
        }
        if(String.fromCharCode(event.keyCode) == 'L'){
          init_speed = init_speed + 0.5;
          if(init_speed > 7){
            init_speed = 7;
          }
          for(var i in enemycircles){
             if(is_negative(enemycircles[i].velocity.x)){
              enemycircles[i].velocity.x = -init_speed;
            }
            else{
              enemycircles[i].velocity.x = init_speed;
            }
            if(is_negative(enemycircles[i].velocity.y)){
              enemycircles[i].velocity.y = -init_speed;
            }
            else{
              enemycircles[i].velocity.y = init_speed;
            }
          }
        
        show_speed = init_speed*2 - 4;
        $('#speed').text("Speed: " + show_speed.toString());
        }
      }

      function handleKeyUp (event) {
        currentlyPressedKeys[event.keyCode] = false;
      }

        

      document.onkeydown = handleKeyDown;
      document.onkeyup = handleKeyUp;
  


      function handleKeys() {
        if (currentlyPressedKeys[65]) {
            // key A
            smove_rightleft = -(init_speed);
        } else if (currentlyPressedKeys[68]) {
            // key D
            smove_rightleft = init_speed;
        } else {
            smove_rightleft = 0;
        }

        if (currentlyPressedKeys[87]) {
            // key W
            smove_updown = -(init_speed);
        } else if (currentlyPressedKeys[83]) {
            // Key S
            smove_updown = init_speed;
        } else {
            smove_updown = 0;
        }

    }


    /*Object for Moving circles and scores */

    function Enemy(x, y, radius) {
        this.velocity = new Two.Vector(init_speed, init_speed);
        this.x = x;
        this.y = y;
        this.radius = radius;
        this.circle = two.makeCircle(x,y, radius);
        this.rect = this.circle.getBoundingClientRect();
        this.circle.fill = "#FF0000";
        this.circle.noStroke();
        this.circle.scale = 0.125;
        this.scale = 0.125;

    }

    function score_circle(x, y, radius) {
      this.x = x;
      this.y = y;
      this.radius = radius;
      this.circle = two.makeCircle(x, y, radius);
      this.rect = this.circle.getBoundingClientRect();
      this.circle.fill = "#00FF00";
      this.circle.noStroke();
      this.circle.scale = 0.2;
      this.scale = 0.2;


    }


    var enemy_count = 0;

    /*event for every frames */
   
    function event_handler(cc, ec, sc) {

      var w = sc[0].scale * sc[0].rect.width / 2;
      var h = sc[0].scale * sc[0].rect.height / 2;
      var wc = cc.scale * cc.rect.width / 2;
      var hc = cc.scale * cc.rect.height / 2;

      if((cc.translation.x + wc >= (sc[0].x-w) && cc.translation.x-wc <= (sc[0].x+w)) && (cc.translation.y+hc >= (sc[0].y-h) && cc.translation.y-hc <= (sc[0].y+h)) )
      {
        score = score + 1;
        enemy_count = enemy_count + 1;
        sc[0].circle.remove();
        var nsc = new score_circle(getrandomX(), getrandomY(), 50);
        if(enemy_count === 2){


        var nec = new Enemy(getrandomX(), getrandomY(), 50);
        ec.push(nec);
        enemy_count = 0;
        }


        sc.pop(sc[0]);
        sc.push(nsc);
        $('#score').text("Score : " + score.toString());

        

      }
      if(score === 30 && !flag['get_thirty']){
          thirty_at_time = x.time();
          //console.log(thirty_at_time);
          flag['get_thirty'] = true;

      }
      if(!flag['at_thirty']){
        var check_time = x.time();
        if(check_time >= 30000){
          thirty_sec_score = score;
          console.log(thirty_sec_score);
          flag['at_thirty'] = true;
        }
      }
      if(!flag["is_over"]){
        time_start();
        flag["is_over"] = !flag["is_over"];
      }

      
    }

      
      /* control circle init */
    
    function init_circle(circle){
      circle.scale = 0.2;
      circle.noStroke();
      circle.fill = '#FF8000';
      circle.rect = circle.getBoundingClientRect();

    }


			var elem = document.getElementById('draw-animation').children[0];
			var two = new Two({type : Two.Types['svg'], width: 700, height: 500 }).appendTo(elem);

			var mcircle = two.makeCircle(getrandomX(), getrandomY(), 50);
      init_circle(mcircle);
		  
      var scc = new score_circle(getrandomX(), getrandomY(), 50);

      scorecircles.push(scc);


      var m = new Enemy(getrandomX(), getrandomY(), 50);

      enemycircles.push(m);

      
			
      /* Starting point = center */
      var currentX = getrandomX();
      var currentY = getrandomY();

      
      $('#score').text("Score : " + score.toString());
       show_speed = init_speed - 2;
      $('#speed').text("Speed: " + show_speed.toString());
    
    			two.bind('update', function(frameCount) {
    			  // This code is called everytime two.update() is called.
    			  // Effectively 60 times per second.
            if(!pause){
                handleKeys();

                 /*Update its postion */ 
                var wc = mcircle.scale * mcircle.rect.width / 2;
                var hc = mcircle.scale * mcircle.rect.width / 2;
                currentX = currentX + smove_rightleft;
                currentY = currentY + smove_updown;
                
                /*control circle hit the wall */

                if ((mcircle.translation.x < wc )){
                  currentX = mcircle.translation.x+3;
                }
                if((mcircle.translation.x > two.width - wc)) {
                      currentX = mcircle.translation.x-3;
                }

                if ((mcircle.translation.y < hc)){
                  currentY = mcircle.translation.y+3;

                }
                if((mcircle.translation.y > two.height - hc)) {
                    currentY = mcircle.translation.y-3;
                }
            
                mcircle.translation.set(currentX, currentY);
               

                /*red circle hit the wall */

                for (var i in enemycircles){
                    var w = enemycircles[i].circle.scale * enemycircles[i].rect.width / 2;
                    var h = enemycircles[i].circle.scale * enemycircles[i].rect.height / 2;

                    enemycircles[i].circle.translation.addSelf(enemycircles[i].velocity)

                    if ((enemycircles[i].circle.translation.x < w && enemycircles[i].velocity.x < 0)
                      || (enemycircles[i].circle.translation.x > two.width - w && enemycircles[i].velocity.x > 0)) {
                      enemycircles[i].velocity.x *= -1;
                    }

                    if ((enemycircles[i].circle.translation.y < h && enemycircles[i].velocity.y < 0)
                      || (enemycircles[i].circle.translation.y > two.height - h && enemycircles[i].velocity.y > 0)) {
                      enemycircles[i].velocity.y *= -1;
                    }

      
                  /*game over status */
                  if((mcircle.translation.x >= (enemycircles[i].circle.translation.x-w) && mcircle.translation.x <= (enemycircles[i].circle.translation.x+w)) && (mcircle.translation.y >= (enemycircles[i].circle.translation.y-h) && mcircle.translation.y <= (enemycircles[i].circle.translation.y+h)) )
                  {

                        top_time = x.time();
                        two.clear();
                        $('#draw-animation').hide();
                        $('.jumbotron').prepend("<div class = 'try_again'><p id = 'total_score'></p><p id = 'Thirty_sec_score'></p><p id = 'sec_at_thirty'></p><p>Press Record or Press space to start again</p><div id = 'btd'><button type='button' class='btn btn-primary' id = 'record'>Record</button><button type='button' class='btn btn-success' id = 'try_button'>Try again</button></div></div>");

                        init_speed = 2;
                        mcircle = two.makeCircle(getrandomX(), getrandomY(), 50);
                        init_circle(mcircle);
                        enemycircles = [];
                        scorecircles = [];
                        mcircle.translation.set(getrandomX(), getrandomY());
                        
                        var m = new Enemy(getrandomX(), getrandomY(), 50);
                        enemycircles.push(m);
                        var nsc = new score_circle(getrandomX(), getrandomY(), 50);
                        scorecircles.push(nsc);
                       
                        
                        show_speed = init_speed - 2;
                        $('#speed').text("Speed: " + show_speed.toString());

                       
                        pause = !pause;
                         
                        var send_score = score;
                        var send_score_t = thirty_sec_score;
                        var send_score_s = thirty_at_time;
      
                      /*Send data to server */        
                       $('#record').on("click", function(){

                             $.ajax({
                                url:'http://thirty.ezoneid.com/php/update_score.php',
                                dataType:'json',
                                type:'POST',
                                data:{'score':send_score, 'send_score_t' : send_score_t, 'send_score_s': send_score_s, 'top_time': top_time},
                                success:function(result){
                                    if(result['result']==true){
                                       console.log(result['score']);
                                       console.log(result['score2']);
                                       console.log(result['score3']);
                                       console.log(result['score4']);
                                      

                                    }
                                }
                            });
                             $('#record').text("Recorded");

                           });
                       

                        $('#total_score').text("Score :" + score.toString());
                       
                        $('#Thirty_sec_score').text("Score at Thirty :" + thirty_sec_score.toString());
                        thirty_at_time_show = (thirty_at_time === 0) ? "You don't even get THIRTY" : formatTime(thirty_at_time);
                        $('#sec_at_thirty').text("Time for Thirty :" + thirty_at_time_show);
                       


                       
                        /*Try again button */

                        $('#try_button').on("click", function(){
                           score = 0;
                          $('.try_again').remove();
                          $('#draw-animation').show();
                           $('#score').text("Score : " + score.toString());
                            thirty_sec_score = 0;
                          time_reset();
                          flag["is_over"] = !flag["is_over"];
                          flag['get_thirty'] = false;
                          flag['at_thirty'] = false;
                          thirty_at_time = 0;
                          pause = !pause;


                        });

                        $(document).keydown(function(e){
                            e.preventDefault();   

                            if (e.keyCode == 32) { 
                              $('#try_button').click();
                               
                            }
                          
                        });


                        break;

                  }


                  }

                 event_handler(mcircle, enemycircles, scorecircles);

              }
            
          


            




    			}).play();

  
  

          


		</script>
		</div>



      </div>
  <div class="fb-like" data-href="http://thirty.ezoneid.com/" data-layout="standard" data-action="like" data-show-faces="false" data-share="true"></div>

      <div class="row marketing">
        <div class="col-lg-6" id = "items">
          <h4>Items</h4>
          <p>Working on it</p>

        </div>

        <div class="col-lg-6" id="achieve">
          <h4>Achievements</h4>
          <p>Working on it</p>
   
        </div>
      </div>