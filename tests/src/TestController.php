<?php

namespace Afoslt\Controllers\Tests;

use Afoslt\Core\Controller;

/**
 * Controller for Unit Tests.
 */
final class TestController extends Controller
{
    /**
     * Test action of an method.
     * 
     * @return void
     */
    public function TestAction (): void { }

    /**
     * Hidden method, with keyword of action.
     * 
     * @return void
     */
    private function HelperAction (): void { }

    /**
     * Just hidden method.
     * 
     * @return void
     */
    private function HiddenMethod (): void { }

    /**
     * Test for name lagrer than word action.
     */
    public function ExampleAction (): void { }
}
