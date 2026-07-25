<?php

namespace App\Enums;

enum SourceType: string
{
    case Book = 'Book';
    case Journal = 'Journal';
    case Medium = 'Medium';
    case Substack = 'Substack';
    case Article = 'Article';
    case PDF = 'PDF';
}
