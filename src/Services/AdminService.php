<?php
namespace App\Services;

use App\Models\DB;
use App\Models\Admin;
use App\Controllers\UsersController;
use App\Utils\JsonKit;

class AdminService
{
    public function getDashboardData(): array
    {
        $totalUsers = DB::table("users")->count();
        $totalTopics = DB::table("topics")->count();
        $totalPosts = DB::table("posts")->count();
        $totalCategories = DB::table("categories")->count();
        $totalReports = DB::table("reports")->count();
        $onlineUsers = DB::table("users")
            ->where("last_activity_time", ">", date("Y-m-d H:i:s", strtotime("-5 minutes")))
            ->count();
        $pendingReports = DB::table("reports")->where("status", "pending")->count();
        $pendingUsers = DB::table("users")->where("is_active", 0)->count();
        $recentUsers = DB::table("users")->orderBy("created_at","desc")->limit(5)->get();
        $recentTopics = DB::table("topics")->orderBy("created_at","desc")->limit(5)->get();

        return compact(
            'totalUsers','totalTopics','totalPosts','totalCategories',
            'totalReports','pendingReports','onlineUsers','pendingUsers',
            'recentUsers','recentTopics'
        );
    }

    public function getUsers(): array
    {
        $users = DB::table("users")->get();
        $safeUsers = [];

        foreach ($users as $user) {
            $safeUsers[] = [
                "id" => $user["id"],
                "username" => $user["username"],
                "displayName" => $user["displayName"],
                "email" => $user["email"],
                "userRole" => UsersController::convertUserRoleIdToStr($user["userRole"]),
                "is_active" => $user["is_active"],
                "is_admin" => $user["is_admin"],
                "userPoints" => $user["userPoints"],
                "profile_picture" => $user["avatar_path"],
                "last_login" => $user["last_login"],
                "created_at" => $user["created_at"],
                "updated_at" => $user["updated_at"],
            ];
        }

        return $safeUsers;
    }

    public function getReports(): array
    {
        $reports = DB::table("reports")->get();
        $safeReports = [];

        foreach ($reports as $report) {
            $postMessage = DB::table("posts")->where("id",$report["post_id"])->first();
            $reportedUsername = DB::table("users")->where("id",$report["reported_by"])->first();

            $safeReports[] = [
                "id" => $report["id"],
                "user_id" => $report["reported_by"],
                "reported_username" => $reportedUsername["username"],
                "post_id" => $report["post_id"],
                "post_message" => $postMessage["content"],
                "status" => $report["status"],
                "reported_at" => $report["reported_at"],
            ];
        }

        return $safeReports;
    }

    public function getSystemInfo(){
        $cpuUsage = "N/A";
        $ramUsage = "N/A";

        $os = PHP_OS_FAMILY;

        if ($os === "Windows") {
            // cpu
            $cpu = @shell_exec("wmic cpu get loadpercentage /Value");
            if ($cpu) {
                foreach (explode("\n", trim($cpu)) as $line) {
                    if (stripos($line, "LoadPercentage") !== false) {
                        $cpuUsage =
                            filter_var($line, FILTER_SANITIZE_NUMBER_INT) . "%";
                    }
                }
            }

            // ram
            $ram = @shell_exec(
                "wmic OS get FreePhysicalMemory,TotalVisibleMemorySize /Value"
            );
            $free = $total = 0;
            if ($ram) {
                foreach (explode("\n", trim($ram)) as $line) {
                    if (stripos($line, "FreePhysicalMemory") !== false) {
                        $free = (int) filter_var(
                            $line,
                            FILTER_SANITIZE_NUMBER_INT
                        );
                    }
                    if (stripos($line, "TotalVisibleMemorySize") !== false) {
                        $total = (int) filter_var(
                            $line,
                            FILTER_SANITIZE_NUMBER_INT
                        );
                    }
                }
            }
            if ($total > 0) {
                $used = $total - $free;
                $ramUsage = round(($used / $total) * 100, 2) . "%";
            }
        } else {
            // linux - mac
            // cpu
            if (file_exists("/proc/stat")) {
                // linux
                $stat1 = file_get_contents("/proc/stat");
                sleep(1);
                $stat2 = file_get_contents("/proc/stat");

                preg_match_all(
                    "/^cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/m",
                    $stat1,
                    $matches1
                );
                preg_match_all(
                    "/^cpu\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/m",
                    $stat2,
                    $matches2
                );

                $idle1 = $matches1[4][0];
                $idle2 = $matches2[4][0];
                $total1 = array_sum(array_slice($matches1, 1, 4))[0];
                $total2 = array_sum(array_slice($matches2, 1, 4))[0];

                $cpuUsage =
                    (($total2 - $total1 - ($idle2 - $idle1)) /
                        ($total2 - $total1)) *
                    100;
                $cpuUsage = round($cpuUsage, 2) . "%";
            } else {
                // mac
                $load = sys_getloadavg();
                $cpuUsage =
                    round(
                        ($load[0] / shell_exec("sysctl -n hw.ncpu")) * 100,
                        2
                    ) . "%";
            }

            // ram
            if ($os === "Linux") {
                $mem = file_get_contents("/proc/meminfo");
                preg_match("/MemTotal:\s+(\d+) kB/", $mem, $totalMem);
                preg_match("/MemAvailable:\s+(\d+) kB/", $mem, $freeMem);

                if (isset($totalMem[1], $freeMem[1])) {
                    $used = $totalMem[1] - $freeMem[1];
                    $ramUsage = round(($used / $totalMem[1]) * 100, 2) . "%";
                }
            } elseif ($os === "Darwin") {
                $total = (int) shell_exec("sysctl -n hw.memsize");
                $free =
                    (int) shell_exec(
                        "vm_stat | grep free | awk '{print $3}' | sed 's/\\.//'"
                    ) * 4096;
                if ($total > 0) {
                    $used = $total - $free;
                    $ramUsage = round(($used / $total) * 100, 2) . "%";
                }
            }
        }

        $diskPath = $os === "Windows" ? "C:" : "/";
        $totalDisk = @disk_total_space($diskPath);
        $freeDisk = @disk_free_space($diskPath);
        $usedDisk = $totalDisk - $freeDisk;
        $diskUsage =
            round($usedDisk / 1024 / 1024 / 1024, 2) .
            "GB / " .
            round($totalDisk / 1024 / 1024 / 1024, 2) .
            "GB";

        $status = [
            "Sistem Durumu" => "Stabil",
            "Sunucu Durumu" => "Aktif",
            "Son Yedekleme" => "Yedek bulunamadı",
            "Disk Kullanımı" => $diskUsage,
            "CPU Kullanımı" => $cpuUsage,
            "RAM Kullanımı" => $ramUsage,
        ];

        return $status;
    }

    public function saveAdminSettings(array $data): bool
    {
        return Admin::saveSettings($data);
    }

    public function getAllSettings(): array
    {
        return Admin::getAll();
    }
}
