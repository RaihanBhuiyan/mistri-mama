<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Setting;
use App\Media;
use Carbon\Carbon;
use App\Mail\ContactUsMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\NotificationController;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP; 

class SiteConfigsController extends Controller
{
    public static function siteConfigs()
    {
        $schedule_charge = Setting::where('name', 'schedule_charge')->first()->value;
        $area_charge = Setting::where('name', 'area_charge')->first()->value;
        $outside_area_id = Setting::where('name', 'outside_area_id')->pluck('value');
        $office_start_time = Setting::where('name', 'office_start_time')->first()->value;
        $office_end_time = Setting::where('name', 'office_end_time')->first()->value;
        $refer = Setting::where('name', 'refer')->first()->value;

        $schedule_times = self::schedule_times($office_start_time, $office_end_time);
        $schedule_dates = self::schedule_dates();
        return [
            'schedule_dates' => $schedule_dates,
            'schedule_times' => $schedule_times,
            'schedule_charge' => $schedule_charge,
            'office_start_time' => $office_start_time,
            'office_end_time' => $office_end_time,
            'area_charge' => $area_charge,
            'outside_area_id' => $outside_area_id,
            'refer' => $refer,
        ];
    }

    public static function schedule_times($office_start_time, $office_end_time)
    {
        $timenow = date('h:00 A');
        $start = strtotime('08:00AM');
        $end = strtotime('11:00PM');
        while ($start !== $end)
        {
            $start = strtotime('+1 hour', $start);
            $to = date('h:i A', $start);
            $form = date('h:i A', strtotime('+1 hour',$start));
            $is_office_hour = ((strtotime($office_start_time) <= $start) && (strtotime($office_end_time) >= strtotime('+1 hour',$start))) ? true : false;
            $range[] = [
                'to' => $to,
                'form' => $form,
                'time' => $to.' - '.$form,
                'is_office_hour' => $is_office_hour,
                'checked' => (strtotime('+3 hour', strtotime($timenow)) == $start) ? true : false,
            ];
        }
        return $range;
    }

    public static function schedule_dates()
    {
        $date = Carbon::today();
        for($i = 0; $i <= 14; $i++)
        {
            $data[] = [
                'date' => $date->format('d-F-Y'),
                'day' => $date->format('l'),
                'checked' => ($i == 0) ? true : false,
            ];
            $date = $date->addDays(1);
        }
        return $data;
    }

    public function mail_contact_us(Request $request)
    {
        $request->validate([
            'phone'    => 'required|size:11|regex:/(01)[0-9]{9}/',
            'email'    => 'required|email|max:255',
            'message'  => 'required|max:255',
        ]);

        $sender_address = $request->email;
        $sender_name = (!empty($request->name)) ? $request->name : $request->email;

        $receiver_name = env('APP_NAME');  
        $receiver_address = env('MAIL_USERNAME');

        try{
            DB::beginTransaction();
            
            $phpMail = new PHPMailer();

            $phpMail->AddAddress($receiver_address, $receiver_name);
            $phpMail->AddReplyTo($receiver_address, $receiver_name);
            $message = view('component.contact_us_mail_template')->with(['data' => $request]);
            
            $phpMail->FromName = $sender_name; 
            $phpMail->From = $sender_address;

            $phpMail->Sender= $sender_address;
            $phpMail->IsHTML(true);
            $phpMail->Host = "mail.ssgbd.com:25"; //your hostname such as ssgbd.com or ip
            $phpMail->IsSMTP();
            $phpMail->Mailer  = "smtp";
            $phpMail->Subject="Contact Us";
            $phpMail->Body=$message;  			
            $phpMail->SMTPAuth=false; 
            $phpMail->Send();

            $phpMail->ClearAddresses();
            $phpMail->ClearAttachments(); 

            NotificationController::contactUsNotification();
            DB::commit();
            return response()->json(['message' => 'Your mail has been received. we will contact you soon.'], 200);
        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Your mail has not send. Please try again'], 400);
    }
    
    public static function sendMailFunction($form, $to, $subject, $body, $filename)
    {
        try {
            $phpMail = new PHPMailer();

            $phpMail->Host = "mail.ssgbd.com:587";
            $phpMail->SMTPAuth = true;
            $phpMail->Username = "".env('MAIL_USERNAME')."";
            $phpMail->Password = "".env('MAIL_PASSWORD')."";
            $phpMail->isSMTP();
            $phpMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $phpMail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            ); 
            
            //Recipients
            $phpMail->addAddress($to, NULL);
            $phpMail->addReplyTo($form, NULL);

            $phpMail->FromName = "".env('APP_NAME').""; 
            $phpMail->From = $form;

            // Attachments
            $phpMail->AddAttachment($filename);

            // Content
            $phpMail->isHTML(true);
            $phpMail->Subject = $subject;
            $phpMail->Body = $body;

            $phpMail->send();

            $phpMail->ClearAddresses();
            $phpMail->ClearAttachments(); 
            return response()->json(['message' => 'Your mail has been send.'], 200);
        } catch (Exception $e) {
            // return response()->json($phpMail->ErrorInfo, 200);
            response()->json(['message' => 'Your mail has not send. Please try again'], 400);
        }
    }

    public function download_corporate_services()
    {
        $headers = ['Content-Type: application/pdf'];
        $fileName = "corporate-services.pdf";
        $pathToFile = public_path('upload/' . $fileName);
        return response()->download($pathToFile, $fileName, $headers);
    }

    public function projects()
    {
        return [
            [
                "title" => "B2B Affiliation",
                "summary" => [
                    "Real Time Dashboard",
                    "Priority Service",
                    "Technician Monitoring Facility",
                    "Separate Service Team",
                    "Product Purchase Support",
                ],
                "image" => "". env('APP_URL') ."/public/frontend/image/C1.jpg",
            ],
            [
                "title" => "B2B Project",
                "summary" => [
                    "Dynamic Dashboard",
                    "Project Process Monitoring",
                    "Project Inventory",
                    "Corporate Discount",
                    "Credit Facility",
                ],
                "image" => "". env('APP_URL') ."/public/frontend/image/C3.jpg",
            ],
            [
                "title" => "B2B Maintenance",
                "summary" => [
                    "Asset Inventory",
                    "Regular Maintenance",
                    "Safety Training",
                    "Corporate Advising Session",
                    "Separate Key Account Manager",
                ],
                "image" => "". env('APP_URL') ."/public/frontend/image/C2.jpg",
            ],
            [
                "title" => "B2B On Demand",
                "summary" => [
                    "On Time Service",
                    "7 Days Service Warranty",
                    "Fast Service During Emergency",
                    "Negotiation Free",
                    "Secured and Trust Worthy Service",
                ],
                "image" => "". env('APP_URL') ."/public/frontend/image/C4.jpg",
            ],
        ];
    }

    public function projects_back()
    {
        return [
            [
                "title" => "B2B Affiliation",
                "title_bn" => "বি ২ বি অ্যাফিলিয়েশন",
                "summary" => [
                    "Real Time Dashboard",
                    "Priority Service",
                    "Technician Monitoring Facility",
                    "Separate Service Team",
                    "Product Purchase Support",
                ],
                "summary_bn" => [
                    "রিয়েল টাইম ড্যাশবোর্ড",
                    "অগ্রাধিকার পরিষেবা",
                    "টেকনিশিয়ান মনিটরিং সুবিধা",
                    "পৃথক পরিষেবা দল",
                    "পণ্য ক্রয় সমর্থন",
                ],
                "image" => "". env('APP_URL') ."/public/frontend/image/C1.jpg",
            ],
            [
                "title" => "B2B Project",
                "title_bn" => "বি ২ বি প্রকল্প",
                "summary" => [
                    "Dynamic Dashboard",
                    "Project Process Monitoring",
                    "Project Inventory",
                    "Corporate Discount",
                    "Credit Facility",
                ],
                "summary_bn" => [
                    "ডায়নামিক ড্যাশবোর্ড",
                    "প্রকল্প প্রক্রিয়া পর্যবেক্ষণ",
                    "প্রকল্পের তালিকা",
                    "কর্পোরেট ছাড়",
                    "ক্রেডিট সুবিধা",
                ],
                "image" => "". env('APP_URL') ."/public/frontend/image/C3.jpg",
            ],
            [
                "title" => "B2B Maintenance",
                "title_bn" => "বি ২ বি রক্ষণাবেক্ষণ",
                "summary" => [
                    "Asset Inventory",
                    "Regular Maintenance",
                    "Safety Training",
                    "Corporate Advising Session",
                    "Separate Key Account Manager",
                ],
                "summary_bn" => [
                    "সম্পদ ইনভেন্টরি",
                    "নিয়মিত রক্ষণাবেক্ষণ",
                    "সুরক্ষা প্রশিক্ষণ",
                    "কর্পোরেট অ্যাডভাইজিং সেশন",
                    "আলাদা কী অ্যাকাউন্ট ম্যানেজার",
                ],
                "image" => "". env('APP_URL') ."/public/frontend/image/C2.jpg",
            ],
            [
                "title" => "B2B On Demand",
                "title_bn" => "বি ২ বি চাহিদা সাপেক্ষে",
                "summary" => [
                    "On Time Service",
                    "7 Days Service Warranty",
                    "Fast Service During Emergency",
                    "Negotiation Free",
                    "Secured and Trust Worthy Service",
                ],
                "summary_bn" => [
                    "টাইম সার্ভিস",
                    "7 দিনের পরিষেবা ওয়্যারেন্টি",
                    "জরুরি অবস্থার সময় দ্রুত পরিষেবা",
                    "আলোচনা থেকে মুক্ত",
                    "সুরক্ষিত এবং বিশ্বাসযোগ্য মূল্য পরিষেবা",
                ],
                "image" => "". env('APP_URL') ."/public/frontend/image/C4.jpg",
            ],
        ];
    }

    public function testimonials()
    {
        return [
            [
                'image' => "". env('APP_URL') ."/public/frontend/image/review/SadmanOrnab_.png",
                'details' => "One of the best service I got. Quick response and faster service delivery I got. Service person behavior is good and very efficient to give the service.",
                'name' => "Sadman Ornab",
                'designation' => "Corporate Executive",
            ],
            [
                'image' => "". env('APP_URL') ."/public/frontend/image/review/HasiburRahmanRahib_.png",
                'details' => "Great Convenient and affordable service. Wish best of luck for future endeavors and keep it up this service quality.",
                'name' => "Hasibur Rahman Rahib",
                'designation' => "Student",
            ],
            [
                'image' => "". env('APP_URL') ."/public/frontend/image/review/DiabMahmudTushar.png",
                'details' => "Mistri mama is a great platform to have household service of electronic items. it's made our daily life easier than before.",
                'name' => "Diab Mahmud Tushar",
                'designation' => "Corporate Executive",
            ],
            [
                'image' => "". env('APP_URL') ."/public/frontend/image/review/TausifAhmed_.png",
                'details' => "Took AC servicing from Mistri Mama and they did a stellar job. Highly recommended.",
                'name' => "Tausif Ahmed",
                'designation' => "Corporate Manager",
            ],
            [
                'image' => "". env('APP_URL') ."/public/frontend/image/review/MizanurRahman_.png",
                'details' => "Services quality is very good and behavior of mistri is just amazing. Thanks to Mistri Mama for excellent services..",
                'name' => "Mizanur Rahman",
                'designation' => "Corporate Executive",
            ],
        ];
    }

    public function media_status_approve($id)
    {
        $media = Media::where('id', $id)->first();
        try
        {
            DB::beginTransaction();
            Media::where('id', $id)->update([
                'status' => 'approve',
            ]);
            NotificationController::documentUploadApproveNotification($media);
            DB::commit();
            toastr()->success('Document has been approve successfully');
            return redirect()->back();
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
        toastr()->error('Document has been approve failed');
        return redirect()->back();
    }

    public function media_status_deny(Request $request, $id)
    {
        $media = Media::where('id', $id)->first();
        try
        {
            DB::beginTransaction();
            if(!empty($media->serviceProvider->lastApprovedMedia->first()))
            {
                $media->serviceProvider->lastApprovedMedia->first()->restore();
            }
            Media::where('id', $id)->update([
                'status' => 'deny',
                'comments' => $request->deny_note,
            ]);
            $media->delete();
            NotificationController::documentUploadDenyNotification($media);
            DB::commit();
            toastr()->success('Document has been deny successfully');
            return redirect()->back();
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
        toastr()->error('Document has been deny failed');
        return redirect()->back();
    }
}
