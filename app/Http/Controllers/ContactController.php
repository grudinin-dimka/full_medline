<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* Подключения */
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

/* Ошибки */
use Throwable;

/* Помощники */
use Carbon\Carbon;

/* Модели */
use App\Models\Contact;
use App\Models\ContactMail;
use App\Models\ContactPhone;
use App\Models\Clinic;
use App\Models\Mail;
use App\Models\Phone;

class ContactController extends Controller
{
   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      GET                          |*/
   /* |___________________________________________________|*/
   public function getContactsAll(Request $request)
   {
      try {
         $clinics = Clinic::all();
         $contacts = Contact::all();

         foreach ($contacts as $contactKey => $contactValue) {
            $contactPhones = ContactPhone::where('contactId', $contactValue->id)->get();
            $phones = [];

            foreach ($contactPhones as $contactPhonesKey => $contactPhonesValue) {
               $phones[] = Phone::where('id', $contactPhonesValue->phoneId)->first();
            };
            $contactValue->phones = $phones;

            $contactMails = ContactMail::where('contactId', $contactValue->id)->get();
            $mails = [];

            foreach ($contactMails as $contactMailsKey => $contactMailsValue) {
               $mails[] = Mail::where('id', $contactMailsValue->mailId)->first();
            };
            $contactValue->mails = $mails;
         };

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => [
               "contacts" => $contacts,
               "clinics" => $clinics,
            ],
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      }
   }

   /* Получение контактов и клиник */
   public function getContactsClinicsAll(Request $request)
   {
      try {
         $contacts = Contact::all();

         foreach ($contacts as $contactKey => $contactValue) {
            $contactPhones = ContactPhone::where('contactId', $contactValue->id)->get();
            $phones = [];

            foreach ($contactPhones as $contactPhonesKey => $contactPhonesValue) {
               $phones[] = Phone::where('id', $contactPhonesValue->phoneId)->first();
            };
            $contactValue->phones = $phones;

            $contactMails = ContactMail::where('contactId', $contactValue->id)->get();
            $mails = [];

            foreach ($contactMails as $contactMailsKey => $contactMailsValue) {
               $mails[] = Mail::where('id', $contactMailsValue->mailId)->first();
            };
            $contactValue->mails = $mails;
            $contactValue->clinic = Clinic::where('id', $contactValue->clinicId)->first();
         }

         return response()->json([
            "success" => true,
            "debug" => false,
            "message" => "Данные получены.",
            "result" => $contacts,
         ], 200);
      } catch (Throwable $e) {
         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      }
   }

   /* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
   /* |                      POST                         |*/
   /* |___________________________________________________|*/
   public function saveContactsChanges(Request $request)
   {
      $contacts = json_decode($request->contacts);
      $arrayID = [];

      // Транзакция
      DB::beginTransaction();

      try {
         foreach ($contacts as $key => $value) {
            // Удаление
            if ($value->delete === true) {
               $contact = Contact::find($value->id);
               $contact->delete();
               continue;
            };

            // Добавление
            if ($value->create === true) {
               $contactCreate = Contact::create([
                  "name" => $value->name,
                  "order" => $value->order,
                  "clinicId" => $value->clinicId ? $value->clinicId : null,
               ]);

               // Телефоны
               $phones = ContactPhone::where('contactId', $value->id)->get();
               foreach ($phones as $phoneKey => $phoneValue) {
                  $phone = Phone::find($phoneValue->phoneId);
                  $phone->delete();
               };

               foreach ($value->phones as $key => $valuePhone) {
                  $phoneCreate = Phone::create([
                     "name" => $valuePhone->name,
                  ]);

                  ContactPhone::create([
                     'contactId' => $contactCreate->id,
                     'phoneId' => $phoneCreate->id,
                  ]);
               }

               // Почты
               $emails = ContactMail::where("contactId", $value->id)->get();
               foreach ($emails as $emailKey => $emailValue) {
                  $email = Mail::find($emailValue->mailId);
                  $email->delete();
               };

               foreach ($value->mails as $key => $valueEmail) {
                  $emailCreate = Mail::create([
                     "name" => $valueEmail->name,
                  ]);

                  ContactMail::create([
                     "contactId" => $contactCreate->id,
                     "mailId" => $emailCreate->id,
                  ]);
               }

               /* Запись нового объекта в массив */
               $arrayID[] = (object) [
                  // Прошлый id
                  "old" => $value->id,
                  // Новый id
                  "new" => $contactCreate->id
               ];
               continue;
            };

            // Обновление
            $contact = Contact::find($value->id);
            $contact->update([
               "name" => $value->name,
               "order" => $value->order,
               "clinicId" => ($value->clinicId == "null" || $value->clinicId == null) ? null : $value->clinicId,
            ]);

            // Телефоны
            $phones = ContactPhone::where("contactId", $value->id)->get();
            foreach ($phones as $phoneKey => $phoneValue) {
               $phone = Phone::find($phoneValue->phoneId);
               $phone->delete();
            };

            foreach ($value->phones as $key => $valuePhone) {
               $phoneCreate = Phone::create([
                  "name" => $valuePhone->name,
               ]);

               ContactPhone::create([
                  "contactId" => $contact->id,
                  "phoneId" => $phoneCreate->id,
               ]);
            }

            // Почты
            $emails = ContactMail::where('contactId', $value->id)->get();
            foreach ($emails as $emailKey => $emailValue) {
               $email = Mail::find($emailValue->mailId);
               $email->delete();
            };

            foreach ($value->mails as $key => $valueEmail) {
               $emailCreate = Mail::create([
                  "name" => $valueEmail->name,
               ]);

               ContactMail::create([
                  'contactId' => $contact->id,
                  'mailId' => $emailCreate->id,
               ]);
            }
         };

         // Сортировка слайдов по порядку от наименьшего до наибольшого
         $contactAll = Contact::all()->SortBy('order');

         // Обновление порядковых номеров
         $count = 0;
         foreach ($contactAll as $contactKey => $contactValue) {
            $count++;
            $contactValue->order = $count;
            $contactValue->save();
         };

         // Фиксация транзакции
         DB::commit();

         return response()->json([
            "success" => true,
            "debug" => true,
            "message" => "Новости изменены.",
            "result" => $arrayID,
         ], 200);
      } catch (Throwable $e) {
         // Отмена транзакции
         DB::rollBack();

         return response()->json([
            "success" => false,
            "debug" => true,
            "message" => $e->getMessage(),
            "result" => null,
         ], 500);
      };
   }
}
