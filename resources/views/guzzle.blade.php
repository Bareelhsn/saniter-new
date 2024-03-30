@foreach ($hasil as $item)
{{-- {{ $item }} --}}
<table border="1">
    <tr>
        <td>{{ $item['nama_material'] }}</td>
        <td>{{ $item['qty'] }}</td>
    </tr>
</table>
@endforeach
