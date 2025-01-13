<?php

namespace App\Http\Controllers;

use DocuSign\eSign\Client\ApiClient;
use DocuSign\eSign\Api\EnvelopesApi;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Model\RecipientViewRequest;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Model\Document;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public function sendForSignature(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'document_path' => 'required|string', // Path to the document to be signed
        ]);

        $apiClient = new ApiClient();
        $apiClient->getConfig()->setHost(env('DOCUSIGN_BASE_URI'));
        $accessToken = $this->getAccessToken();

        $apiClient->getConfig()->addDefaultHeader('Authorization', "Bearer $accessToken");
        $envelopeApi = new EnvelopesApi($apiClient);

        $document = new Document([
            'documentBase64' => base64_encode(file_get_contents($validated['document_path'])),
            'name' => 'Rental Agreement',
            'fileExtension' => 'pdf',
            'documentId' => '1',
        ]);

        $signer = new Signer([
            'email' => $validated['email'],
            'name' => $validated['name'],
            'recipientId' => '1',
            'routingOrder' => '1',
        ]);

        $envelopeDefinition = new EnvelopeDefinition([
            'emailSubject' => 'Please sign the document',
            'documents' => [$document],
            'recipients' => ['signers' => [$signer]],
            'status' => 'sent',
        ]);

        $envelopeSummary = $envelopeApi->createEnvelope(env('DOCUSIGN_ACCOUNT_ID'), $envelopeDefinition);

        return response()->json(['message' => 'Document sent for signature', 'envelope_id' => $envelopeSummary->getEnvelopeId()]);
    }
    public function saveSignature(Request $request)
    {
        $request->validate([
            'signature' => 'required|string', // Base64-encoded signature
        ]);

        $signatureData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->signature));

        file_put_contents(public_path('signatures/signature.png'), $signatureData);

        return response()->json(['message' => 'Signature saved successfully']);
    }


    private function getAccessToken()
    {
        $apiClient = new ApiClient();
        $apiClient->getOAuth()->setBasePath(env('DOCUSIGN_BASE_URI') . "/oauth/token");
        $response = $apiClient->getOAuth()->getToken(env('DOCUSIGN_CLIENT_ID'), env('DOCUSIGN_CLIENT_SECRET'), ['signature']);
        return $response->getAccessToken();
    }
}
