<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading" v-if="player !== null">{{ map_name }} - Cash: &pound;{{ player.cash }} - X: {{ player.tile.x }} - Y: {{ player.tile.y }}</div>
                    <div class="panel-body">

                        <table v-show="!terminal_open" border="1" style="color:white; text-align:center">
                            <tr v-for="(y, index) in map_data" :key="`map_data-${index}`">
                                <td width="64" height="64" v-for="(x, bindex) in y" :key="`map_data-${bindex}`" :style="{ 'background-color': x.background.color }">
                                    <i v-if="x.tile.location" :class="getName(x.tile.location.location_type)"></i>
                                    <template v-if="checkSize(x.tile,0)">
                                        <img src="images/player.png" />
                                    </template>

                                    <p style="position:absolute" v-show="x.tile.users.length > 0">{{ x.tile.users.length }}</p>


                                </td>
                            </tr>
                        </table>
                        <div v-show="terminal_open">
                            <terminal-component></terminal-component>
                        </div>

                        <hr/>

                        <button class="btn btn-danger" :disabled="moving" v-on:click="openTerminal()">Toggle Terminal</button>

                        <hr/>

                        <h1 v-show="!terminal_open">Actions</h1>

                        <button class="btn btn-success" v-show="!terminal_open" :disabled="moving" v-on:click="movePlayer('north')">Up</button>
                        <button class="btn btn-success" v-show="!terminal_open" :disabled="moving" v-on:click="movePlayer('south')">Down</button>
                        <button class="btn btn-success" v-show="!terminal_open" :disabled="moving" v-on:click="movePlayer('west')">Left</button>
                        <button class="btn btn-success" v-show="!terminal_open" :disabled="moving" v-on:click="movePlayer('east')">Right</button>

                        <button v-show="onShop && !terminal_open" class="btn btn-success" v-on:click="enterShop">Enter Shop</button>
                        <button v-show="onHouse && !terminal_open" class="btn btn-success">Enter House (Coming Soon)</button>

                        <!--<hr v-show="!terminal_open"/>-->
                        <!--<h1 v-show="!terminal_open">Build</h1>-->

                        <!--<button class="btn btn-success" v-show="!terminal_open" :disabled="moving" v-on:click="movePlayer('west')">House (Cost)</button>-->
                        <!--<button class="btn btn-success" v-show="!terminal_open" :disabled="moving" v-on:click="movePlayer('west')">Shop (Cost)</button>-->
                        <!--<button class="btn btn-success" v-show="!terminal_open" :disabled="moving" v-on:click="movePlayer('west')">Network (Cost)</button>-->

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getMapData();
            this.updatePlayer();
        },
        data() {
            return {
                map_data: null,
                x: 0,
                y: 0,
                city: null,
                moving: false,
                terminal_open: false,
                player: null
            }
        },
        props: ['map_id','map_name'],
        computed: {
          onShop() {
              if (this.player) {
                  if (this.player.tile.location) {
                      return this.player.tile.location.location_type === 0;
                  }
              }
              return false;
          },
            onHouse() {
                if (this.player) {
                    if (this.player.tile.location) {
                        return this.player.tile.location.location_type === 1;
                    }
                }
                return false;
            }
        },
        methods: {
            enterShop() {
              this.terminal_open = true;
            },
            getMapData() {
                var self = this;
                axios.get('/map/' + this.map_id + '/data')
                    .then(function(response){
                        self.map_data = response.data;
                        console.log("Got data");
                        self.updatePlayer();
                    });
            },
            updatePlayer() {

                var self = this;
                axios.get('/player')
                    .then(function(response){
                        self.player = response.data;
                        console.log("Got player data");
                        console.log(response.data);
                    });

            },
            checkSize(tile, size) {
                return tile.users.length > size;
            },
            getName(location_type) {

                switch (location_type) {
                    case 1: return 'fas fa-home';
                    case 0: return 'fas fa-shopping-basket';

                }
            },
            openTerminal() {
              this.terminal_open = !this.terminal_open;
            },
            movePlayer(direction) {
                //console.log("Move player " + direction);

                // first disable buttons
                this.moving = true;
                var self = this;
                // move player
                axios.get('/player/move/' + direction).then(function(response){
                    self.getMapData();
                    console.log("Moved Player " + direction );
                    console.log(response.data)
                });

                // re-enable buttons
                this.moving = false;

            }

        }

    }
</script>
