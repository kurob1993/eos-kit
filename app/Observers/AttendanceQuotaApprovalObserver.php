<?php

namespace App\Observers;

use App\Message;
use App\Models\AttendanceQuotaApproval;
use App\Models\Stage;
use App\Models\Status;
use App\Notifications\OvertimeApprovalMessage;
use Session;

class AttendanceQuotaApprovalObserver
{
    public function updated(AttendanceQuotaApproval $currentAqa)
    {
        // mencari AttendanceQuota dari approval ini
        $aq = $currentAqa->attendanceQuota;

        // to adalah karyawan yang mengajukan
        $to = $aq->user;

        // from adalah dari atasan
        $from = $currentAqa->user;

        // menyimpan catatan pengiriman pesan
        $message = new Message;

        switch ($currentAqa->sequence) {
            case 1:
                if ($currentAqa->is_approved) {

                    // message history
                    $messageAttribute = sprintf('Overtime partially approved from %s to %s',
                        $from->personnelNoWithName, $to->personnelNoWithName);

                } else if ($currentAqa->is_rejected) {
                    // batalkan persetujuan, ubah stage menjadi denied
                    $aq->stage_id = Stage::deniedStage()->id;

                    // simpan perubahan Stage untuk AttendanceQuota
                    $aq->save();

                    // reject status pada second approval
                    $secondAqa = $aq->second_approval;
                    $secondAqa->status_id = Status::rejectStatus()->id;
                    $secondAqa->save();

                    // message history
                    $messageAttribute = sprintf('Overtime rejected at first sequence from %s to %s',
                        $from->personnelNoWithName, $to->personnelNoWithName);
                }
                break;
            case 2:
                if ($currentAqa->is_approved) {
                    // mencari approval pertama
                    $firstApproval = $aq->first_approval;

                    // apakah approval pertama sudah disetujui pada sequence sebelumnya
                    // jika ya proses ke Send to SAP
                    if ($firstApproval->is_approved) {
                        // NEED TO IMPLEMENT FLOW STAGE (send to SAP)
                        $aq->stage_id = Stage::sentToSapStage()->id;

                        // message history
                        $messageAttribute = sprintf('Overtime completely approved from %s to %s',
                            $from->personnelNoWithName, $to->personnelNoWithName);
                    }
                } elseif ($currentAqa->is_rejected) {
                    // NEED TO IMPLEMENT FLOW STAGE (send to SAP)
                    $aq->stage_id = Stage::deniedStage()->id;

                    // message history
                    $messageAttribute = sprintf('Overtime rejected at second sequence from %s to %s',
                        $from->personnelNoWithName, $to->personnelNoWithName);
                }
                // simpan perubahan Stage untuk AttendanceQuota
                $aq->save();
                break;
        }

        // simpan data message history lainnya
        $message->setAttribute('from', $from->id);
        $message->setAttribute('to', $to->id);
        $message->setAttribute('message', $messageAttribute);

        // simpan message history
        $message->save();

        // sistem mengirim email notifikasi dari atasan ke
        // karyawan yang mengajukan
        $to->notify(new OvertimeApprovalMessage($from, $currentAqa));
    }

    public function updating(AttendanceQuotaApproval $currentAqa)
    {
        // mencari semua AttendanceQuota dari approval ini
        $aq = $currentAqa->attendanceQuota;

        if ($currentAqa->sequence == 2) {
            // mencari approval pertama
            $firstApproval = $aq->first_approval;

            // apakah approval pertama masih menunggu persetujuan
            if ($firstApproval->is_waiting) {
                // tampilkan pesan bahwa persetujuan sebelumnya harus diselesaikan
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => "Tidak dapat melakukan persetujuan karena data persetujuan "
                    . "pada proses sebelumnya belum diselesaikan. "
                    . $firstApproval->employee->personnelNoWithName,
                ]);
                // batalkan persetujuan
                return false;
            }
        }


    }
}
