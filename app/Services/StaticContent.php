<?php

namespace App\Services;

use App\Models\PageSlider;
use App\Models\FooterLink;
use App\Models\PageFooter;
use App\Models\StaticPage;
use App\Models\HomePageWidget;

class StaticContent
{


    // function to get slider slides

	public function getSliderList()
    {   
        $sliders = PageSlider::where('status', PageSlider::ACTIVE)->get();
        return  $sliders;
    }

    // function to get footer content

    public function getFooter()
    {   
        $footer = PageFooter::where('status', PageFooter::ACTIVE)->first();
        $footerLink = FooterLink::where('status', FooterLink::ACTIVE)->orderBy('id', 'desc')->take(10)->get();

        $data['footer_sidebar'] = $footer;
        $data['footer_sidebar_link'] = $footerLink;
        return  $data;
    }

    // function to get page by slug

    public function getPage($slug)
    {   
        $data = StaticPage::where('page_slug',$slug)->first();
        return  $data;
    }

    // function to get widgets

    public function getWidgetList()
    {   
        $widgets = HomePageWidget::where('status', HomePageWidget::ACTIVE)->where('type', HomePageWidget::TYPE_SLIDER)->orderBy('id', 'asc')->take(3)->get();
        return  $widgets;
    }

    // function to get news_letter_form

    public function getNewsLettertList()
    {   
        $widgets = HomePageWidget::where('status', HomePageWidget::ACTIVE)->where('type', HomePageWidget::TYPE_NEWS_LETTER)->first();
        return  $widgets;
    }

    // function to get widgets

    public function getSupportWidgetList()
    {   
        $widgets = HomePageWidget::where('status', HomePageWidget::ACTIVE)->where('type', HomePageWidget::TYPE_SUPPORT)->orderBy('id', 'asc')->take(3)->get();
        return  $widgets;
    }

    // function to get seo page

    public function getSeoWidget($page_type)
    {   
        $widget = HomePageWidget::where('status', HomePageWidget::ACTIVE)->where('type', HomePageWidget::TYPE_OTHERS)->where('page_type',$page_type)->first();
        return  $widget;
    }
    
}
