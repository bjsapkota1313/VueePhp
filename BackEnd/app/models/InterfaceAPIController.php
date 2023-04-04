<?php

namespace Models;

interface InterfaceAPIController  // so every api controllers should have these methods
{
    function getAll();

    function getOne($id);

    function create();

    function update($id);

    function delete($id);

}