<?php

namespace App\Policies;
use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    // Warga bisa mengedit jika laporan itu miliknya dan statusnya masih baru
    public function update(User $user, Report $report): bool
    {
        return $user->id === $report->user_id && $report->status === 'baru';
    }

    // Warga bisa menghapus laporannya sendiri jika status masih baru
    public function delete(User $user, Report $report): bool
    {
        if ($user->isAdmin()) {
            return true; // admin bisa hapus laporan 
        }
        return $user->id === $report->user_id && $report->status === 'baru';
    }

    // Hanya Admin yang bisa mengubah status dan memberi catatan penyelesaian
    public function updateStatus(User $user, Report $report): bool
    {
        return $user->isAdmin();
    }
}