

{{ Form::open(array('route' => 'graph.store')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class'=>'form-control')) }}
    </div>


    <div class="form-group">
        {{ Form::label('streamId', 'Stream') }}
        {{ Form::select('streamId', [""]+$streamDropdown, null, array('class'=>'form-control')) }}
    </div>


    <div class="form-group">
        {{ Form::label('field', 'Field') }}
        {{ Form::select('field', [], null, array('class'=>'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('time_period', 'Time Period') }}
        {{ Form::select('time_period', $timePeriods, null, array('class'=>'form-control')) }}
    </div>


    {{ Form::submit('Save', array('class'=>'btn btn-primary')) }}

    <script>

        var streams = {{ json_encode($streams) }};

        function updateFieldDropdown()
        {
            for(var i in streams) {
                if (streams[i].id == $("#streamId").find(":selected").val()) {
                    console.log(streams[i].fields);

                    $("#field").empty();
                    for (var x in streams[i].fields) {
                        //console.log(streams[i].fields[x]);
                        if (streams[i].fields[x].type == 'data') {
                            $("#field").append($("<option value=\""+streams[i].fields[x].key+"\">"+streams[i].fields[x].name+"</option>"));
                        }
                    }

                }
            }
        }

        updateFieldDropdown();

        $("#streamId").change(function() {
            updateFieldDropdown();
        });

    </script>

{{ Form::close() }}