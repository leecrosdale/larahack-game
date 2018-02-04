<template>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Terminal</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="terminal" style="min-height:10px; height:100px;"><img src="images/larahack-os.png"/></div>
                        <div class="terminal col-md-12">
                            <div class="row" v-for="(line, index) in lines" v-html="line.message_value" ><b>{{ line.message_key }} </b></div>

                        </div>
                    </div>
                    <div class="row"> <br/>
                        <div class="col-md-10">
                            <input class="form-control" type="text" v-on:keyup.enter="executeCommand" v-model="command" placeholder="Type commands here" />
                        </div>
                        <div class="col-md-2 col-sm-10 btn btn-success" v-on:click="executeCommand">Send</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log("Mounted Terminal");
            this.getLines();
        },
        data() {
            return {
                lines: null,
                command: null
            }
        },
        methods: {
            getLines() {
                var self = this;

                console.log("Getting lines");
                axios.get('/player/terminal/lines').then(function(response){
                    self.lines = response.data;
                    console.log("Got terminal lines");
                });
            },
            executeCommand() {

                var self = this;

                axios.post('/player/command', { command: this.command }).then(function(response){
                    console.log("Executed Command");
                    console.log(response.data);
                    self.getLines();
                    self.command = '';
                });

            }
        }
    }
</script>
