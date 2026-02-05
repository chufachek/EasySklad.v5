<?php
class JournalController
{
    public function list()
    {
        require_auth();
        require_company();
        $documents = Document::listForCompany(current_company_id());
        view('journal/index', array('documents' => $documents));
    }

    public function show($id)
    {
        require_auth();
        require_company();
        $doc = Document::find(current_company_id(), $id);
        if (!$doc) {
            http_response_code(404);
            view('layout/404');
            return;
        }
        $items = Document::items($id);
        view('journal/show', array('document' => $doc, 'items' => $items));
    }
}
