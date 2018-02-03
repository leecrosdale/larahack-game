<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ map_id }}</div>
                    <div class="panel-body">
                        <table border="1" style="color:white; text-align:center">
                            <tr v-for="(y, index) in map_data" :key="`map_data-${index}`">
                                <td width="64" height="64" v-for="(x, bindex) in y" :key="`map_data-${bindex}`" :style="{ 'background-color': x.background.color }">{{ x.tile.x }} - {{ x.tile.y }}</td>
                            </tr>
                        </table>

                        <hr/>
                        <button class="btn btn-success">North</button>
                        <button class="btn btn-success">East</button>
                        <button class="btn btn-success">South</button>
                        <button class="btn btn-success">West</button>

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
                city: null
            }
        },
        props: ['map_id'],
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
            }

        }

    }
</script>
