<?php

namespace App\Imports;

use App\Models\Participant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ParticipantsImport implements ToModel, WithHeadingRow, WithValidation
{
    
    protected $eventId;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $qrCode = uniqid() . '_' . $row['email'];

        return new Participant([
            'name'       => $row['name'],
            'email'      => $row['email'],
            'department' => $row['department'] ?? null,
            'qr_code'    => $qrCode,
            'event_id'   => $this->eventId,
        ]);
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:participants,email',
            'department' => 'nullable|string|max:255',
        ];
    }
}
