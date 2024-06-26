<?php

return [
    'accepted' => ':Attribute må aksepteres.',
    'accepted_if' => ':Attribute må aksepteres når :other er :value.',
    'active_url' => ':Attribute er ikke en gyldig URL.',
    'after' => ':Attribute må være en dato etter :date.',
    'after_or_equal' => ':Attribute må være en dato etter eller lik :date.',
    'alpha' => ':Attribute må kun bestå av bokstaver.',
    'alpha_dash' => ':Attribute må kun bestå av bokstaver, tall og bindestreker.',
    'alpha_num' => ':Attribute må kun bestå av bokstaver og tall.',
    'array' => ':Attribute må være en matrise.',
    'before' => ':Attribute må være en dato før :date.',
    'before_or_equal' => ':Attribute må være en dato før eller lik :date.',
    'between' => [
        'array' => ':Attribute må ha mellom :min - :max elementer.',
        'file' => ':Attribute må være mellom :min - :max kilobytes.',
        'numeric' => ':Attribute må være mellom :min - :max.',
        'string' => ':Attribute må være mellom :min - :max tegn.',
    ],
    'boolean' => ':Attribute må være sann eller usann',
    'confirmed' => ':Attribute er ikke likt bekreftelsesfeltet.',
    'current_password' => 'Oppgitt passord er feil.',
    'date' => ':Attribute er ikke en gyldig dato.',
    'date_equals' => ':Attribute må være en dato lik :date.',
    'date_format' => ':Attribute samsvarer ikke med formatet :format.',
    'declined' => ':Attribute må avvises.',
    'declined_if' => ':Attribute må avvises når :other er :value.',
    'different' => ':Attribute og :other må være forskellige.',
    'digits' => ':Attribute må ha :digits siffer.',
    'digits_between' => ':Attribute må være mellom :min og :max siffer.',
    'dimensions' => ':Attribute har ugyldige bildedimensjoner.',
    'distinct' => ':Attribute har en duplisert verdi.',
    'email' => ':Attribute må være en gyldig e-postadresse.',
    'ends_with' => ':Attribute må ende med en av følgende: :values.',
    'enum' => 'Det valgte :attribute er ugyldig.',
    'exists' => 'Det valgte :attribute er ugyldig.',
    'file' => ':Attribute må være en fil.',
    'filled' => ':Attribute må fylles ut.',
    'gt' => [
        'array' => ':Attribute må ha flere enn :value elementer.',
        'file' => ':Attribute må være større enn :value kilobytes.',
        'numeric' => ':Attribute må være større enn :value.',
        'string' => ':Attribute må være større enn :value tegn.',
    ],
    'gte' => [
        'array' => ':Attribute må ha :value elementer eller flere.',
        'file' => ':Attribute må være større enn eller lik :value kilobytes.',
        'numeric' => ':Attribute må være større enn eller lik :value.',
        'string' => ':Attribute må være større enn eller lik :value tegn.',
    ],
    'image' => ':Attribute må være et bilde.',
    'in' => 'Det valgte :attribute er ugyldig.',
    'in_array' => 'Det valgte :attribute eksisterer ikke i :other.',
    'integer' => ':Attribute må være et heltall.',
    'ip' => ':Attribute må være en gyldig IP-adresse.',
    'ipv4' => ':Attribute må være en gyldig IPv4-adresse.',
    'ipv6' => ':Attribute må være en gyldig IPv6-addresse.',
    'json' => ':Attribute må være på JSON-format.',
    'lt' => [
        'array' => ':Attribute må ha færre enn :value elementer.',
        'file' => ':Attribute må være mindre enn :value kilobytes.',
        'numeric' => ':Attribute må være mindre enn :value.',
        'string' => ':Attribute må være kortere enn :value tegn.',
    ],
    'lte' => [
        'array' => ':Attribute må ikke ha flere enn :value elementer.',
        'file' => ':Attribute må være mindre enn eller lik :value kilobytes.',
        'numeric' => ':Attribute må være mindre enn eller lik :value.',
        'string' => ':Attribute må være kortere enn eller lik :value tegn.',
    ],
    'mac_address' => ':Attribute må være en gyldig MAC-adresse.',
    'max' => [
        'array' => ':Attribute må ikke ha flere enn :max elementer.',
        'file' => ':Attribute må ikke være større enn :max kilobytes.',
        'numeric' => ':Attribute må ikke være større enn :max.',
        'string' => ':Attribute må ikke være større enn :max tegn.',
    ],
    'mimes' => ':Attribute må være en fil av typen: :values.',
    'mimetypes' => ':Attribute må være en fil av typen: :values.',
    'min' => [
        'array' => ':Attribute må ha minst :min elementer.',
        'file' => ':Attribute må være minst :min kilobytes.',
        'numeric' => ':Attribute må være minst :min.',
        'string' => ':Attribute må være minst :min tegn.',
    ],
    'multiple_of' => ':Attribute må være flere av :value',
    'not_in' => 'Den valgte :attribute er ugyldig.',
    'not_regex' => 'Formatet på :attribute er ugyldig.',
    'numeric' => ':Attribute må være et tall.',
    'password' => 'Passordet er feil.',
    'present' => ':Attribute må eksistere.',
    'prohibited' => ':Attribute felt er forbudt.',
    'prohibited_if' => ':Attribute felt er forbudt når :other er :value.',
    'prohibited_unless' => ':Attribute felt er forbudt med mindre :other er i :values.',
    'prohibits' => ':Attribute forbyr :other å være til stede.',
    'regex' => 'Formatet på :attribute er ugyldig.',
    'required' => ':Attribute må fylles ut.',
    'required_array_keys' => ':Attribute feltet må inneholde oppføringer for: :values.',
    'required_if' => ':Attribute må fylles ut når :other er :value.',
    'required_unless' => ':Attribute er påkrevd med mindre :other finnes blant verdiene :values.',
    'required_with' => ':Attribute må fylles ut når :values er utfylt.',
    'required_with_all' => ':Attribute er påkrevd når :values er oppgitt.',
    'required_without' => ':Attribute må fylles ut når :values ikke er utfylt.',
    'required_without_all' => ':Attribute er påkrevd når ingen av :values er oppgitt.',
    'same' => ':Attribute og :other må være like.',
    'size' => [
        'array' => ':Attribute må inneholde :size elementer.',
        'file' => ':Attribute må være :size kilobytes.',
        'numeric' => ':Attribute må være :size.',
        'string' => ':Attribute må være :size tegn lang.',
    ],
    'starts_with' => ':Attribute må starte med en av følgende: :values.',
    'string' => ':Attribute må være en tekststreng.',
    'timezone' => ':Attribute må være en gyldig tidssone.',
    'unique' => ':Attribute er allerede i bruk.',
    'uploaded' => ':Attribute kunne ikke lastes opp.',
    'url' => 'Formatet på :attribute er ugyldig.',
    'uuid' => ':Attribute må være en gyldig UUID.',
    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
];
