<?php

/**
 * Class ZP_Transport
 *
 * Handles the legacy [transport] shortcode by delegating to specific classes.
 *
 * @package    Api_End
 * @subpackage Api_End/includes/transport
 * @author     Garikai Dzoma <garikaib@gmail.com>
 */
class ZP_Transport
{
    /**
     * Initialize the class and register the shortcode.
     */
    public function __construct()
    {
        add_shortcode('transport', array($this, 'render_shortcode'));
    }

    /**
     * Render the shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public function render_shortcode($atts)
    {
        $atts = shortcode_atts(
            array(
                'type' => 'zupco', // Default type
            ),
            $atts,
            'transport'
        );

        $type = strtolower($atts['type']);

        // Delegate based on type
        switch ($type) {
            case 'zupco':
                if (class_exists('ZP_Zupco')) {
                    $zupco = new ZP_Zupco();
                    return $zupco->render_shortcode($atts);
                }
                break;

            case 'busfares':
                if (class_exists('ZP_Bus_Fares')) {
                    $bus_fares = new ZP_Bus_Fares();
                    return $bus_fares->render_shortcode($atts);
                }
                break;

            case 'tollgates':
                // Migrated to zimpricecheck-tools (v2 API), September 2026 — see
                // ZPC_Prices::render_tollgates() / ZPC_Tollgate_Source. The dispatcher
                // stays here (still backs the unrelated zupco/busfares types below) but
                // delegates this case to the new shortcode rather than the old class.
                return do_shortcode('[tollgates type="standard"]');

            case 'tollgates_prem':
                return do_shortcode('[tollgates type="premium"]');

            case 'zinara':
                // Migrated to zimpricecheck-tools (v2 API), September 2026 — see
                // ZPC_Prices::render_zinara_licence() / ZPC_Zinara_Licence_Source.
                return do_shortcode('[zinara-license]');

            default:
                // Fallback or error
                return '<p>Invalid transport type specified.</p>';
        }

        return '<p>Transport module not found.</p>';
    }
}
