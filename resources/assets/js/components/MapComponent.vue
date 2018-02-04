<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ map_name }}</div>
                    <div class="panel-body">



                        <table v-show="!terminal_open" border="1" style="color:white; text-align:center">
                            <tr v-for="(y, index) in map_data" :key="`map_data-${index}`">
                                <td width="64" height="64" v-for="(x, bindex) in y" :key="`map_data-${bindex}`" :style="{ 'background-color': x.background.color }">
                                    <i v-if="x.tile.location" class="fas fa-home"></i>

                                    <!--<i v-if="checkSize(x.tile,1)" class="fas fa-users"></i>-->


                                    <template v-if="checkSize(x.tile,0)">
                                        <img src="images/player.png" />
                                    </template>

                                    {{ x.tile.users.length }}




                                </td>
                            </tr>
                        </table>


                        <div v-show="terminal_open">
                            <terminal-component></terminal-component>
                        </div>

                        <hr/>
                        <button class="btn btn-success" v-show="!terminal_open" :disabled="moving" v-on:click="movePlayer('north')">North</button>
                        <button class="btn btn-success" v-show="!terminal_open" :disabled="moving" v-on:click="movePlayer('east')">East</button>
                        <button class="btn btn-success" v-show="!terminal_open" :disabled="moving" v-on:click="movePlayer('south')">South</button>
                        <button class="btn btn-success" v-show="!terminal_open" :disabled="moving" v-on:click="movePlayer('west')">West</button>

                        <button class="btn btn-danger" :disabled="moving" v-show="!terminal_open" v-on:click="openTerminal()">Open Terminal</button>


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
        },
        data() {
            return {
                map_data: null,
                x: 0,
                y: 0,
                city: null,
                moving: false,
                terminal_open: true,
            }
        },
        props: ['map_id','map_name'],
        computed: {
            checkPlayerLocation() {
                if (this.x > 5) {
                    return getMapData();
                }
            }
        },
        methods: {
            getMapData() {
                var self = this;
                axios.get('/map/' + this.map_id + '/data')
                    .then(function(response){
                        self.map_data = response.data;
                        console.log("Got data");
                    });
            },
            checkSize(tile, size) {
                return tile.users.length > size;
            },
            openTerminal() {
              this.terminal_open = true;
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
