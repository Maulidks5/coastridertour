<?php
namespace App\Http\Controllers;
use App\Models\ContactInquiry;
use App\Mail\NewWebsiteSubmission;
use App\Support\SendsSubmissionEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class SafariRequestController extends Controller {
    use SendsSubmissionEmail;
    public function store(Request $request): RedirectResponse {
        $data = $request->validate([
            'name'=>['required','string','max:120'], 'preferred_contact'=>['required', Rule::in(['whatsapp','email'])],
            'email'=>['nullable','required_if:preferred_contact,email','email','max:190'],
            'whatsapp'=>['nullable','required_if:preferred_contact,whatsapp','string','min:7','max:40','regex:/^[0-9+()\-\s]+$/'],
            'start_date'=>['required','date','after_or_equal:today'], 'days'=>['required','integer','min:1','max:30'],
            'adults'=>['required','integer','min:1','max:30'], 'children'=>['required','integer','min:0','max:20'],
            'parks'=>['required','array','min:1'], 'parks.*'=>['string', Rule::in(['Mikumi','Nyerere','Serengeti','Ngorongoro','Tarangire','Lake Manyara','Not sure'])],
            'accommodation'=>['required', Rule::in(['Budget','Mid-range','Luxury','Not sure'])],
            'arrival_point'=>['required', Rule::in(['Zanzibar','Dar es Salaam','Arusha','Other','Not sure'])],
            'budget'=>['nullable','string','max:80'], 'notes'=>['nullable','string','max:2000'], 'consent'=>['accepted'], 'website'=>['nullable','max:0'],
        ]);
        $message = implode("\n", [
            "Preferred start: {$data['start_date']}", "Duration: {$data['days']} day(s)",
            "Guests: {$data['adults']} adult(s), {$data['children']} child(ren)",
            'Parks: '.implode(', ', $data['parks']), "Accommodation: {$data['accommodation']}",
            "Arrival/start point: {$data['arrival_point']}", 'Budget: '.($data['budget'] ?: 'Not provided'),
            '', 'Notes:', $data['notes'] ?: 'None',
        ]);
        $inquiry = ContactInquiry::query()->create([
            'reference'=>'SAF-'.now()->format('ymd').'-'.Str::upper(Str::random(5)), 'name'=>trim($data['name']),
            'email'=>filled($data['email'] ?? null) ? trim($data['email']) : null, 'phone'=>filled($data['whatsapp'] ?? null) ? trim($data['whatsapp']) : null, 'preferred_contact'=>$data['preferred_contact'], 'subject'=>'Safari Planning Request',
            'source'=>'safari', 'message'=>$message, 'status'=>'new',
        ]);
        $this->sendSubmissionEmail(new NewWebsiteSubmission(
            heading: 'New Safari Planning Request', reference: $inquiry->reference,
            details: ['Name' => $inquiry->name, 'Preferred contact' => str($data['preferred_contact'])->title()->toString(), 'Email' => $inquiry->email, 'WhatsApp' => $inquiry->phone, 'Start date' => $data['start_date'], 'Duration' => $data['days'].' day(s)', 'Guests' => $data['adults'].' adult(s), '.$data['children'].' child(ren)', 'Parks' => $data['parks'], 'Accommodation' => $data['accommodation'], 'Arrival point' => $data['arrival_point'], 'Budget' => $data['budget'], 'Notes' => $data['notes']],
            replyToAddress: $inquiry->email, replyToName: $inquiry->name,
        ));
        return back()->with('success', 'Your safari request has been received. Our team will contact you with the next steps.');
    }
}
