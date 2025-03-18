<div style=display:inline-block>
    @php
        $colors = ['red', 'blue', 'green', 'yellow', 'orange', 'grey', 'purple', 'black', 'beige', 'red'];
    @endphp
    @foreach ($students as $x)
        <h1>{{ $x->name }} hello

        </h1>
        @foreach ($x->posts as $post)
            <p>{{ $post->title }}</p>
        @endforeach
    @endforeach

</div>
