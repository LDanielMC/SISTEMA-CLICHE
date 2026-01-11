@props(['url'])

<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
    {{-- Estilo basado en: font-serif text-4xl font-bold tracking-tight text-[#004481] --}}
    <span style="
        font-family: ui-serif, Georgia, Cambria, 'Times New Roman', Times, serif; /* font-serif */
        font-size: 36px;          /* text-4xl */
        line-height: 40px;        /* text-4xl line-height */
        font-weight: 700;         /* font-bold */
        letter-spacing: -0.025em; /* tracking-tight */
        color: #004481;           /* Tu color azul personalizado */
        ">
        CLICHÉ
    </span>
</a>
</td>
</tr>
