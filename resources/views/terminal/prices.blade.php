<table class="table table-striped" style="border: 1px solid green">
    <tr>
        <th>Part</th>
        <th>Price</th>
    </tr>


    @foreach ($upgrades as $k => $upgrade)
        <tr>
            <td>{{ $k }}</td>
            <td>{{ $upgrade * $computer->{$k} }}</td>
        </tr>
    @endforeach


</table>