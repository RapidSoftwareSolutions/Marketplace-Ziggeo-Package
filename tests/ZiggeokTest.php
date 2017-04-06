<?php
namespace Tests;

require_once(__DIR__ . '/../src/Models/checkRequest.php');

class ZiggeoTest extends BaseTestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testRoutes($url) {
        $response = $this->runApp("POST", '/api/Ziggeo/'.$url);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function dataProvider() {
        return [
            ['getAllTickets'],
            ['createTicket'],
            ['getSingleTicket'],
            ['getAllTicketFields'],
            ['getContacts'],
            ['getSingleContact'],
            ['getAllContactFields'],
            ['getAllCompanyFields'],
            ['createForumCategory'],
            ['updateForumCategory'],
            ['getForumCategory'],
            ['getAllForumCategories'],
            ['deleteForumCategory'],
            ['updateForum'],
            ['getAllForumsFromCategory'],
            ['getForum'],
            ['deleteForum'],
            ['createTopic'],
            ['updateTopic'],
            ['deleteTopic'],
            ['getSingleTopic'],
            ['createComment'],
            ['updateComment'],
            ['deleteComment'],
            ['monitorTopic'],
            ['unMonitorTopic'],
            ['monitorForum'],
            ['unMonitorForum'],
            ['getUserMonitoredTopic'],
            ['getMonitorStatusForTopic'],
            ['getMonitorStatusForForum'],
            ['updateTicket'],
            ['assignTicket'],
            ['getAllAgents'],
            ['createCompany'],
            ['updateCompany'],
            ['getSingleCompany'],
            ['getAllCompanies'],
            ['deleteCompany'],
            ['deleteCompanyDomains'],
            ['createSolutionCategory'],
            ['updateSolutionCategory'],
            ['createTranslatedSolutionCategory'],
            ['getSolutionCategory'],
            ['getAllSolutionCategories'],
            ['deleteSolutionCategory'],
            ['createSolutionFolder'],
            ['createTranslatedSolutionFolder'],
            ['updateSolutionFolder'],
            ['getSolutionFolder'],
            ['getAllSolutionFolders'],
            ['deleteSolutionFolder'],
            ['createSolutionArticle'],
            ['createTranslatedSolutionArticle'],
            ['updateSolutionArticle'],
            ['getSingleAgent'],
            ['getCurrentlyAgent'],
            ['updateAgent'],
            ['getTicketConversations'],
            ['addNoteToTicket'],
            ['createContact'],
            ['updateContact'],
            ['makeAgent'],
            ['deleteContact'],
            ['deleteAgent'],
            ['createForum'],
            ['getSolutionArticle'],
            ['getAllSolutionArticles'],
            ['deleteSolutionArticle'],
            ['createTimeEntry'],
            ['getAllTimeEntries'],
            ['getByTicketTimeEntry'],
            ['updateTimeEntry'],
            ['deleteTimeEntry'],
            ['toggleTimer'],
            ['getAllSurveys'],
            ['createGroup'],
            ['getAllRoles'],
            ['getSingleRole'],
            ['getAllGroups'],
            ['getGroup'],
            ['updateGroup'],
            ['deleteGroup'],
            ['deleteTicket'],
            ['restoreTicket'],
            ['getSurvey']
        ];
    }
}