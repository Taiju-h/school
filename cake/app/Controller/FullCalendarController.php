/**
 * index method
 *
 * @return void
 */
  public function index() {
    if($this->request->is('ajax')) {
      $this->Schedule->recursive = 0;


      $target_start = $this->request->query['start'];
      $target_end = $this->request->query['end'];

      $schedules = $this->paginate(array(
        'OR' => array(
          'Schedule.start between ? and ?' => array($target_start_time, $target_end_time),
          'Schedule.end between ? and ?' => array($target_start_time, $target_end_time),
        ),
      ));

      $this->set('schedules', $schedules);

      $this->render('jsonlist');
    }
  }

/**
 * add method
 *
 * @return void
 */
  public function add() {
    if ($this->request->is('post')) {
      $this->Schedule->create();

      if ($this->Schedule->save($this->request->data)) {

        $this->Session->setFlash(
          __('The %s has been saved', __('schedule')),
          'alert',
          array(
            'plugin' => 'TwitterBootstrap',
            'class' => 'alert-success'
          )
        );

        if(!$this->request->is('ajax')) {
          $this->redirect(array('action' => 'index'));
        } else {
          $this->autoRender = false;
          return $this->Schedule->id;
        }
      } else {
        $this->Session->setFlash(
          __('The %s could not be saved. Please, try again.', __('schedule')),
          'alert',
          array(
            'plugin' => 'TwitterBootstrap',
            'class' => 'alert-error'
          )
        );
      }
    } else {
      if(!empty($this->request->query['start'])) {
        $this->request->data['Schedule']['start'] = strtotime($this->request->query['start']);
      }
      if(!empty($this->request->query['end'])) {
        $this->request->data['Schedule']['end'] = strtotime($this->request->query['end']);
      }
      if(!empty($this->request->query['allDay'])) {
        $this->request->data['Schedule']['allDay'] = strtotime($this->request->query['allDay']);
      }
    }

    if($this->request->is('ajax')) {
      $this->render('modal_add');
    }
  }

/**
 * edit method
 *
 * @param string $id
 * @return void
 */
  public function edit($id = null) {
    $this->Schedule->id = $id;
    if (!$this->Schedule->exists()) {
      throw new NotFoundException(__('Invalid %s', __('schedule')));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->Schedule->save($this->request->data)) {
        $this->Session->setFlash(
          __('The %s has been saved', __('schedule')),
          'alert',
          array(
            'plugin' => 'TwitterBootstrap',
            'class' => 'alert-success'
          )
        );
        if(!$this->request->is('ajax')) {
          $this->redirect(array('action' => 'index'));
        } else {
          $this->autoRender = false;
          return true;
        }
      } else {
        $this->Session->setFlash(
          __('The %s could not be saved. Please, try again.', __('schedule')),
          'alert',
          array(
            'plugin' => 'TwitterBootstrap',
            'class' => 'alert-error'
          )
        );
      }
    } else {
      $this->request->data = $this->Schedule->find('first', array(
        'conditions' => array(
          'Schedule.id' => $id
        ),
        'contain' => false
      ));
    }

    if($this->request->is('ajax')) {
      $this->render('modal_edit');
    }
  }

/**
 * change method
 *
 * @param string $id
 * @return void
 */
  public function change($id, $start, $end, $allDay = false) {
    $this->Schedule->id = $id;
    if (!$this->Schedule->exists()) {
      throw new NotFoundException(__('Invalid %s', __('schedule')));
    }

    $schedule = $this->Schedule->getMiniData($id);

    $schedule['Schedule']['start'] = $start;
    $schedule['Schedule']['end'] = $end;
    $schedule['Schedule']['allDay'] = $allDay;
    $schedule['Schedule']['modified'] = null;

    $this->autoRender = false;

    if ($this->Schedule->save($schedule)) {
      return true;
    } else {
      return false;
    }
  }

/**
 * delete method
 *
 * @param string $id
 * @return void
 */
  public function delete($id = null) {
    if (!$this->request->is('post')) {
      throw new MethodNotAllowedException();
    }
    $this->Schedule->id = $id;
    if (!$this->Schedule->exists()) {
      throw new NotFoundException(__('Invalid %s', __('schedule')));
    }
    if ($this->Schedule->delete()) {
      $this->Session->setFlash(
        __('The %s deleted', __('schedule')),
        'alert',
        array(
          'plugin' => 'TwitterBootstrap',
          'class' => 'alert-success'
        )
      );
      if(!$this->request->is('ajax')) {
        $this->redirect(array('action' => 'index'));
      } else {
        $this->autoRender = false;
        return true;
      }
    }
    $this->Session->setFlash(
      __('The %s was not deleted', __('schedule')),
      'alert',
      array(
        'plugin' => 'TwitterBootstrap',
        'class' => 'alert-error'
      )
    );
    $this->redirect(array('action' => 'index'));
  }