<?php

final class PhabricatorAuditMailReceiver extends PhabricatorObjectMailReceiver {

  public function isEnabled() {
    return PhabricatorApplication::isClassInstalled(
      'PhabricatorAuditApplication');
  }

  protected function getObjectPattern() {
    return 'COMMIT[1-9]\d*';
  }

  protected function loadObject($pattern, PhabricatorUser $viewer) {
    $id = (int)preg_replace('/^COMMIT/', '', $pattern);

    return id(new DiffusionCommitQuery())
      ->setViewer($viewer)
      ->withIDs(array($id))
      ->needAuditRequests(true)
      ->executeOne();
  }

  protected function getTransactionReplyHandler() {
    return new PhabricatorAuditReplyHandler();
  }

}
