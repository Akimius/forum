<?php

namespace App\Http\Requests;

use App\Reply;
use App\Rules\SpamFree;
use App\Thread;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Gate;
use vendor\project\StatusTest;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Gate::allows('create', new Reply());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'body' =>['required', new SpamFree()],
        ];
    }

    protected function failedAuthorization(): void
    {
        throw new ThrottleRequestsException('Your are posting too frequently. Please, take a break');
    }

    public function persist(Thread $thread)
    {
        return $thread->addReply(
            [
                'body' => request('body'),
                'user_id' => auth()->id(),
            ]
        )->load('owner');
    }
}
